<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class EleveApiController extends Controller
{
    /**
     * Liste des élèves
     */
    public function index()
    {
        $eleves = Eleve::with(['classe', 'paren'])
            ->latest()
            ->paginate(200);

        return response()->json([
            'success' => true,
            'data' => $eleves
        ]);
    }

    /**
     * Créer un élève
     */
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
            'nom_mere'       => 'nullable|string|max:255',
            'prenom_mere'    => 'nullable|string|max:255',
            'telephone_pere' => 'nullable|string|max:255',
            'telephone_mere' => 'nullable|string|max:255',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $eleve = DB::transaction(function () use ($validated, $request) {

            // Génération matricule
            $prefix = '23122025';

            $lastMatricule = Eleve::lockForUpdate()
                ->where('matricule', 'like', $prefix . '%')
                ->orderByDesc('matricule')
                ->value('matricule');

            $nextNumber = $lastMatricule
                ? intval(substr($lastMatricule, -4)) + 1
                : 1;

            $matricule = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Photo
            $photoPath = null;

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')
                    ->store('photos_eleves', 'public');
            }

            // Création élève
            $eleve = Eleve::create(array_merge($validated, [
                'matricule' => $matricule,
                'photo'     => $photoPath,
            ]));

            // Inscription
            $inscription = Inscription::create([
                'eleve_id'  => $eleve->id,
                'classe_id' => $validated['classe_id'],
                'annee_id'  => $validated['annee_id'],
                'statut'    => $validated['statut'],
            ]);

            // Initialisation frais
            $fraisClasse = DB::table('classe_frais')
                ->join('frais', 'classe_frais.frais_id', '=', 'frais.id')
                ->where('classe_frais.classe_id', $validated['classe_id'])
                ->select('frais.id', 'frais.montant')
                ->get();

            foreach ($fraisClasse as $frais) {

                DB::table('inscription_frais')->insert([
                    'inscription_id' => $inscription->id,
                    'frais_id'       => $frais->id,
                    'annee_id'       => $validated['annee_id'],
                    'montant_frais'  => $frais->montant,
                    'montant_paye'   => 0,
                    'reste'          => $frais->montant,
                    'statut'         => 'non_payé',
                    'est_arriere'    => false,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            return $eleve;
        });

        return response()->json([
            'success' => true,
            'message' => 'Élève créé avec succès',
            'data' => $eleve
        ], 201);
    }

    /**
     * Afficher un élève
     */
    public function show(Eleve $eleve)
    {
        $eleve->load(['classe', 'paren.user']);

        return response()->json([
            'success' => true,
            'data' => $eleve
        ]);
    }

    /**
     * Modifier un élève
     */
    public function update(Request $request, Eleve $eleve)
    {
        $validated = $request->validate([
            'nom'             => 'required|string|max:255',
            'prenom'          => 'required|string|max:255',
            'numeducmaster'   => 'nullable|string|max:255',
            'date_naissance'  => 'nullable|date',
            'sexe'            => 'required|in:M,F',
            'nationalite'     => 'required|string|max:100',
            'lieu_naissance'  => 'required|string|max:255',
            'matricule'       => 'nullable|string|max:50|unique:eleves,matricule,' . $eleve->id,
            'classe_id'       => 'required|exists:classes,id',
            'statut'          => 'required|in:passant,redoublant',
            'annee_id'        => 'required|exists:annees,id',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Gestion photo
        if ($request->hasFile('photo')) {

            if ($eleve->photo) {
                Storage::disk('public')->delete($eleve->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('photos_eleves', 'public');
        }

        $eleve->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Élève mis à jour avec succès',
            'data' => $eleve
        ]);
    }

    /**
     * Supprimer un élève
     */
    public function destroy(Eleve $eleve)
    {
        if ($eleve->photo) {
            Storage::disk('public')->delete($eleve->photo);
        }

        $eleve->delete();

        return response()->json([
            'success' => true,
            'message' => 'Élève supprimé avec succès'
        ]);
    }

    /**
     * Élèves par classe
     */
    public function getParClasse($classeId)
    {
        $classe = Classe::with('eleves.paren')
            ->find($classeId);

        return response()->json([
            'success' => true,
            'data' => $classe ? $classe->eleves : []
        ]);
    }

    /**
     * Importation des photos
     */
    public function importPhotos(Request $request)
    {
        $request->validate([
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $photos = $request->file('photos');

        if (!$photos) {

            return response()->json([
                'success' => false,
                'message' => 'Aucune photo sélectionnée.'
            ], 400);
        }

        $doublons = [];
        $importes = 0;
        $replace = $request->has('replace');

        foreach ($photos as $photo) {

            try {

                $matricule = pathinfo(
                    $photo->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $eleve = Eleve::where('matricule', $matricule)->first();

                if (!$eleve) {
                    continue;
                }

                // Déjà une photo
                if ($eleve->photo && !$replace) {
                    $doublons[] = $eleve;
                    continue;
                }

                // Suppression ancienne photo
                if ($eleve->photo && $replace) {

                    if (Storage::disk('public')->exists($eleve->photo)) {
                        Storage::disk('public')->delete($eleve->photo);
                    }
                }

                // Nouvelle photo
                $path = $photo->store('photos_eleves', 'public');

                $eleve->update([
                    'photo' => $path
                ]);

                $importes++;

            } catch (\Exception $e) {

                logger()->error(
                    "Erreur import photo : " . $e->getMessage()
                );

                continue;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $replace
                ? "Remplacement effectué avec succès"
                : "Import terminé avec succès",
            'photos_importees' => $importes,
            'doublons' => $doublons
        ]);
    }
}