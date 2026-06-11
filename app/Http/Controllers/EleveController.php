<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Annee;
use App\Models\AnneeClasseFrais;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class EleveController extends Controller
{
   public function index(Request $request)
{
    $cycles = Cycle::all();
    $annees = Annee::all();
    $classes = Classe::all();

    $query = Inscription::with(['classe', 'annee', 'paren'])->latest();

    // Filtre par cycle (via classe → cycle)
    if ($request->filled('cycle_id')) {
        $query->whereHas('classe', function ($q) use ($request) {
            $q->where('cycle_id', $request->cycle_id);
        });
    }

    // Filtre par classe
    if ($request->filled('classe_id')) {
        $query->where('classe_id', $request->classe_id);
    }

    // Filtre par année
    if ($request->filled('annee_id')) {
        $query->where('annee_id', $request->annee_id);
    }

    $eleves = $query->paginate(50);

    return view('eleves.index', compact('eleves', 'cycles', 'annees', 'classes'));
}

    public function create()
    {
       $anneeEnCours = Annee::where('en_cours', true)->first();
        $cycles = Cycle::with('classes')->orderBy('nom')->get();

        return view('eleves.create', compact('anneeEnCours', 'cycles'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nom'            => 'required|string|max:255',
        'prenom'         => 'required|string|max:255',
        'numeducmaster'  => 'nullable|string|max:255',
        'date_naissance' => 'nullable|date',
        'sexe'           => 'required|in:M,F',
        'nationalite'    => 'required|string|max:100',
        'lieu_naissance' => 'required|string|max:255',
        'classe_id'      => 'required|exists:classes,id',
        'statut'         => 'required|in:passant,redoublant',
        'annee_id'       => 'required|exists:annees,id',
        'nom_pere'       => 'nullable|string|max:255',
        'prenom_pere'    => 'nullable|string|max:255',
       // 'nom_mere'       => 'nullable|string|max:255',
       // 'prenom_mere'    => 'nullable|string|max:255',
        'telephone_pere' => 'nullable|string|max:255',
       // 'telephone_mere' => 'nullable|string|max:255',
        'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    DB::transaction(function () use ($validated, $request) {

        // 1️⃣ Génération matricule
        $prefix = '23122025';

        $lastMatricule = Eleve::lockForUpdate()
            ->where('matricule', 'like', $prefix . '%')
            ->orderByDesc('matricule')
            ->value('matricule');

        $nextNumber = $lastMatricule
            ? intval(substr($lastMatricule, -4)) + 1
            : 1;

        $matricule = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 2️⃣ Gestion photo
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                ->store('photos_eleves', 'public');
        }

        // 3️⃣ Création élève
     $eleve = Eleve::create([
    'matricule'      => $matricule,
    'nom'            => $validated['nom'],
    'prenom'         => $validated['prenom'],
    'numeducmaster'  => $validated['numeducmaster'] ?? null,
    'date_naissance' => $validated['date_naissance'] ?? null,
    'sexe'           => $validated['sexe'],
    'nationalite'    => $validated['nationalite'],
    'lieu_naissance' => $validated['lieu_naissance'],
     'classe_id'      => $validated['classe_id'],
     'annee_id'      => $validated['annee_id'],
      'statut'         => $validated['statut'],

    'nom_pere'       => $validated['nom_pere'] ?? null,
    'prenom_pere'    => $validated['prenom_pere'] ?? null,
    //'nom_mere'       => $validated['nom_mere'] ?? null,
    //'prenom_mere'    => $validated['prenom_mere'] ?? null,
    'telephone_pere' => $validated['telephone_pere'] ?? null,
   // 'telephone_mere' => $validated['telephone_mere'] ?? null,

    'photo'          => $photoPath,
]);

        // 4️⃣ Inscription
        $inscription = Inscription::create([
            'eleve_id'  => $eleve->id,
            'classe_id' => $validated['classe_id'],
            'annee_id'  => $validated['annee_id'],
            'statut'    => $validated['statut'],
        ]);

        // 5️⃣ Initialisation des frais
        $fraisClasse = DB::table('annee_classe_frais')
            ->join('frais', 'annee_classe_frais.frais_id', '=', 'frais.id')
            ->where('annee_classe_frais.classe_id', $validated['classe_id'])
            ->where('annee_classe_frais.annee_id', $validated['annee_id'])
            ->select(
                'frais.id as frais_id',
                'frais.nom as frais_nom',
                'annee_classe_frais.montant'
            )
            ->get();

        foreach ($fraisClasse as $frais) {

         DB::table('inscription_frais')->updateOrInsert(
    [
        'inscription_id' => $inscription->id,
        'frais_id'       => $frais->frais_id,
        'annee_id'       => $validated['annee_id'],
    ],
    [
        'montant_frais' => $frais->montant,
        'montant_paye'  => 0,
        'reste'         => $frais->montant,
        'statut'        => 'non_payé',
        'est_arriere'   => false,
        'updated_at'    => now(),
        'created_at'    => now(),
    ]
);
        }
    });

    return redirect()
        ->route('eleves.create')
        ->with(
            'success',
            'Élève, inscription et frais initialisés avec succès.'
        );
}

    public function show(Eleve $eleve)
    {
        $eleve->load(['classe', 'paren.user']);
        return view('eleves.show', compact('eleve'));
    }

    public function edit(Eleve $eleve)
    {
        $classes = Classe::orderBy('nom')->get();
        $annees = Annee::orderBy('nom')->get();

        return view('eleves.edit', compact('eleve', 'classes', 'annees'));
    }

public function update(Request $request, Eleve $eleve)
{
   
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'numeducmaster' => 'nullable|string|max:255',
        'date_naissance' => 'nullable|date',
        'sexe' => 'required|in:M,F',
        'nationalite' => 'required|string|max:100',
        'lieu_naissance' => 'required|string|max:255',
        'matricule' => 'nullable|string|max:50|unique:eleves,matricule,' . $eleve->id,
        'classe_id' => 'required|exists:classes,id',
        'statut' => 'required|in:passant,redoublant',
        'annee_id' => 'required|exists:annees,id',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // 🔥 TRAITEMENT PHOTO
    if ($request->hasFile('photo')) {

        // supprimer ancienne photo
        if ($eleve->photo) {
            Storage::disk('public')->delete($eleve->photo);
        }

        // stocker nouvelle
        $validated['photo'] = $request->file('photo')->store('photos_eleves', 'public');
    }

    $eleve->update($validated);

    return redirect()->route('eleves.index')
        ->with('success', 'Élève mis à jour avec succès.');
}
    public function destroy(Eleve $eleve)
    {
        // Supprimer photo
        if ($eleve->photo) {
            Storage::disk('public')->delete($eleve->photo);
        }

        $eleve->delete();

        return redirect()->route('eleves.index')
            ->with('success', 'Élève supprimé.');
    }

    public function getParClasse($classeId)
    {
        $classe = Classe::with('eleves.paren')->find($classeId);

        return response()->json([
            'eleves' => $classe ? $classe->eleves : []
        ]);
    }


public function importPhotos(Request $request)
{
    $request->validate([
        'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $photos = $request->file('photos');

    if (!$photos) {
        return back()->with('error', 'Aucune photo sélectionnée.');
    }

    $doublons = [];
    $importes = 0;
    $nonConformes = [];
    $replace = $request->has('replace');

    foreach ($photos as $photo) {

        try {

            // 🔥 Nom du fichier sans extension
            $filename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

            // 🔥 Format attendu : matricule_nom_prenom
            $parts = explode('_', $filename);

            if (count($parts) < 3) {
                $nonConformes[] = $filename;
                continue;
            }

            [$matricule, $nom, $prenom] = $parts;

            $eleve = Eleve::where('matricule', $matricule)->first();

            if (!$eleve) {
                $nonConformes[] = $filename;
                continue;
            }

            // 🔍 Vérification stricte nom + prénom
            if (
                strtolower(trim($eleve->nom)) !== strtolower(trim($nom)) ||
                strtolower(trim($eleve->prenom)) !== strtolower(trim($prenom))
            ) {
                $nonConformes[] = $filename;
                continue;
            }

            // ⚠️ déjà une photo
            if ($eleve->photo && !$replace) {
                $doublons[] = $eleve;
                continue;
            }

            // 🧹 suppression ancienne photo si replace
            if ($eleve->photo && $replace) {
                if (Storage::disk('public')->exists($eleve->photo)) {
                    Storage::disk('public')->delete($eleve->photo);
                }
            }

            // 📸 stockage
            $path = $photo->store('photos_eleves', 'public');

            $eleve->update([
                'photo' => $path
            ]);

            $importes++;

        } catch (\Exception $e) {
            logger()->error("Erreur import photo: " . $e->getMessage());
            continue;
        }
    }

    // ⚠️ CAS DOUBLONS
    if (count($doublons) > 0 && !$replace) {
        return back()->with([
            'warning_doublon' => true,
            'doublons' => $doublons
        ]);
    }

    // ⚠️ CAS NON CONFORMES
    if (count($nonConformes) > 0) {
        return back()->with([
            'warning_format' => true,
            'non_conformes' => $nonConformes
        ]);
    }

    // 🎯 SUCCESS
    if ($replace) {
        return back()->with('success',
            "✔ Remplacement effectué avec succès ($importes photo(s))"
        );
    }

    return back()->with('success',
        "✔ Import terminé ($importes photo(s) enregistrée(s))"
    );
}

public function photosForm()
{
    return view('eleves.photos');
}




}