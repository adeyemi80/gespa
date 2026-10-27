<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Note;
use App\Models\Moyenne;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function index(Request $request)
{
    $cycles = Cycle::all();
    $annees = Annee::all();
    $classes = Classe::all();

    $query = Inscription::orderBy('id')->with(['eleve', 'classe', 'annee'])->latest();

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

    $inscriptions = $query->paginate(50);

    return view('inscriptions.index', compact(
        'inscriptions', 'cycles', 'annees', 'classes'
    ));
}

    public function create()
    {
        return view('inscriptions.create', [
            'eleves'  => Eleve::all(),
            'classes' => Classe::all(),
            'annees'  => Annee::all(),
            'notes'   => Note::all(),
        ]);
    }

    public function store(Request $request)
    {
        // 1️⃣ Validation
        $request->validate([
            'eleve_id'  => 'required|exists:eleves,id',
            'classe_id' => 'required|exists:classes,id',
            'annee_id'  => 'required|exists:annees,id',
           
        ]);

        // 2️⃣ Pas de double inscription la même année
        $existe = Inscription::where('eleve_id', $request->eleve_id)
            ->where('annee_id', $request->annee_id)
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'eleve_id' => 'Cet élève est déjà inscrit pour cette année scolaire.'
            ])->withInput();
        }

        // 3️⃣ Récupération moyenne
        $moyenne = Moyenne::where('eleve_id', $request->eleve_id)
            ->where('classe_id', $request->classe_id)
            ->where('annee_id', $request->annee_id)
            ->value('moyenne_generale');

        if (is_null($moyenne)) {
            return back()->withErrors([
                'moyenne' => 'La moyenne annuelle n’est pas encore calculée.'
            ])->withInput();
        }

        $decision = $moyenne >= 10 ? 'passé' : 'redoublé';

        // 4️⃣ Transaction globale
        DB::transaction(function () use ($request, $moyenne, $decision) {

            // ➕ Création inscription
            $inscription = Inscription::create([
                'eleve_id'         => $request->eleve_id,
                'classe_id'        => $request->classe_id,
                'annee_id'         => $request->annee_id,
                'note_id'          => $request->note_id,
                'moyenne_annuelle' => $moyenne,
                'decision'         => $decision,
                'date_inscription' => now(),
            ]);

            // ➕ Génération automatique des frais
            $classe = Classe::with('frais')->findOrFail($request->classe_id);

            foreach ($classe->frais as $frais) {
                DB::table('inscription_frais')->insert([
                    'inscription_id' => $inscription->id,
                    'frais_id'       => $frais->id,
                    'annee_id'       => $request->annee_id,
                    'montant_frais'  => $frais->montant,
                    'montant_paye'   => 0,
                    'reste'          => $frais->montant,
                    'statut'         => 'non_payé',
                    'est_arriere'    => false,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        });

        return redirect()
            ->route('inscriptions.index')
            ->with('success', 'Inscription créée avec frais générés automatiquement.');
    }

    public function show(Inscription $inscription)
    {
        return view('inscriptions.show', compact('inscription'));
    }

    public function edit(Inscription $inscription)
{
    return view('inscriptions.edit', [
        'inscription' => $inscription,
        'eleves'      => Eleve::all(),
        'cycles'      => Cycle::all(),
        'classes'     => Classe::all(),
        'annees'      => Annee::all(),
        
    ]);
}

   public function update(Request $request, Inscription $inscription)
{
    // ============================================================
    // 1. VALIDATION DES DONNÉES
    // ============================================================

    $request->validate([
        'eleve_id'         => 'required|exists:eleves,id',
        'cycle_id'         => 'required|exists:cycles,id',
        'classe_id'        => 'required|exists:classes,id',
        'annee_id'         => 'required|exists:annees,id',
        'date_inscription' => 'required|date',
    ]);

    // ============================================================
    // 2. VÉRIFIER QUE LA CLASSE APPARTIENT AU CYCLE
    // ============================================================

    $classe = Classe::where('id', $request->classe_id)
        ->where('cycle_id', $request->cycle_id)
        ->first();

    if (!$classe) {
        return back()
            ->withErrors([
                'classe_id' => 'La classe sélectionnée ne appartient pas au cycle choisi.'
            ])
            ->withInput();
    }

    // ============================================================
    // 3. VÉRIFIER QU'IL N'Y A PAS D'AUTRE INSCRIPTION
    //    POUR LE MÊME ÉLÈVE ET LA MÊME ANNÉE
    // ============================================================

    $existe = Inscription::where('eleve_id', $request->eleve_id)
        ->where('annee_id', $request->annee_id)
        ->where('id', '!=', $inscription->id)
        ->exists();

    if ($existe) {
        return back()
            ->withErrors([
                'eleve_id' => 'Cet élève est déjà inscrit pour cette année scolaire.'
            ])
            ->withInput();
    }

    // ============================================================
    // 4. RÉCUPÉRER LES FRAIS CONFIGURÉS POUR
    //    ANNÉE + CLASSE
    // ============================================================

    $fraisClasseAnnee = DB::table('annee_classe_frais')
        ->where('annee_id', $request->annee_id)
        ->where('classe_id', $request->classe_id)
        ->get();

    if ($fraisClasseAnnee->isEmpty()) {
        return back()
            ->withErrors([
                'classe_id' =>
                    'Aucun frais n\'est configuré pour cette année scolaire et cette classe.'
            ])
            ->withInput();
    }

    // ============================================================
    // 5. VÉRIFIER QU'AUCUN FRAIS N'A UN MONTANT NULL
    // ============================================================

    foreach ($fraisClasseAnnee as $frais) {

        if ($frais->montant === null) {

            $nomFrais = DB::table('frais')
                ->where('id', $frais->frais_id)
                ->value('nom');

            $nomFrais = $nomFrais ?? 'Frais ID ' . $frais->frais_id;

            return back()
                ->withErrors([
                    'classe_id' =>
                        "Le montant du frais « {$nomFrais} » n'est pas défini pour cette année et cette classe."
                ])
                ->withInput();
        }
    }

    // ============================================================
    // 6. TRANSACTION
    // ============================================================

    try {

        DB::transaction(function () use (
            $request,
            $inscription,
            $fraisClasseAnnee
        ) {

            // ========================================================
            // 6.1 METTRE À JOUR L'INSCRIPTION
            // ========================================================

            $inscription->update([
                'eleve_id'         => $request->eleve_id,
                'classe_id'        => $request->classe_id,
                'annee_id'         => $request->annee_id,
                'date_inscription' => $request->date_inscription,
            ]);

            // ========================================================
            // 6.2 RÉCUPÉRER LES FRAIS DÉJÀ ASSOCIÉS
            // ========================================================

            $fraisExistants = DB::table('inscription_frais')
                ->where('inscription_id', $inscription->id)
                ->get()
                ->keyBy('frais_id');

            // ========================================================
            // 6.3 AJOUTER OU METTRE À JOUR LES FRAIS
            // ========================================================

            foreach ($fraisClasseAnnee as $frais) {

                $montant = (float) $frais->montant;

                // ----------------------------------------------------
                // Le frais existe déjà pour cette inscription
                // ----------------------------------------------------

                if (isset($fraisExistants[$frais->frais_id])) {

                    $fraisInscription = $fraisExistants[$frais->frais_id];

                    $montantPaye = (float) $fraisInscription->montant_paye;

                    // Le montant payé ne peut pas dépasser
                    // le nouveau montant du frais.
                    if ($montantPaye > $montant) {
                        $montantPaye = $montant;
                    }

                    $reste = max(0, $montant - $montantPaye);

                    // Déterminer automatiquement le statut
                    if ($montantPaye <= 0) {
                        $statut = 'non_payé';
                    } elseif ($montantPaye < $montant) {
                        $statut = 'partiellement_payé';
                    } else {
                        $statut = 'soldé';
                    }

                    DB::table('inscription_frais')
                        ->where('id', $fraisInscription->id)
                        ->update([
                            'montant_frais' => $montant,
                            'montant_paye'  => $montantPaye,
                            'reste'         => $reste,
                            'statut'        => $statut,
                            'updated_at'    => now(),
                        ]);
                }

                // ----------------------------------------------------
                // Le frais n'existe pas encore
                // ----------------------------------------------------

                else {

                    DB::table('inscription_frais')->insert([
                        'inscription_id' => $inscription->id,
                        'frais_id'       => $frais->frais_id,
                        'annee_id'       => $request->annee_id,
                        'montant_frais'  => $montant,
                        'montant_paye'   => 0,
                        'reste'          => $montant,
                        'statut'         => 'non_payé',
                        'est_arriere'    => false,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }
        });

    } catch (\Exception $e) {

        return back()
            ->withErrors([
                'error' => 'Erreur lors de la mise à jour de l\'inscription : '
                    . $e->getMessage()
            ])
            ->withInput();
    }

    // ============================================================
    // 7. REDIRECTION
    // ============================================================

    return redirect()
        ->route('inscriptions.index')
        ->with(
            'success',
            'Inscription et frais associés mis à jour avec succès.'
        );
}
    public function destroy(Inscription $inscription)
    {
        $inscription->delete();

        return redirect()
            ->route('inscriptions.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }


}
