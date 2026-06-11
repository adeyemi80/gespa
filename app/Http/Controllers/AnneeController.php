<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\AnneeClasse;
use App\Models\AnneeClasseFrais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneeController extends Controller
{
    /**
     * Liste toutes les années scolaires.
     */
    public function index()
    {
        $annees = Annee::orderBy('id', 'desc')->get();
        return view('annees.index', compact('annees'));
    }

    /**
     * Affiche le formulaire de création d'année scolaire.
     */
    public function create()
    {
        return view('annees.create');
    }

    /**
     * Enregistre une nouvelle année scolaire.
     */


public function store(Request $request)
{
    $request->validate([
        'nom'   => 'required|unique:annees,nom',
        'debut' => 'required|date',
        'fin'   => 'required|date|after:debut',
    ]);

    DB::transaction(function () use ($request) {

        // ✅ Création de la nouvelle année scolaire
        $annee = Annee::create([
            'nom'       => $request->nom,
            'debut'     => $request->debut,
            'fin'       => $request->fin,
            'en_cours'  => $request->boolean('en_cours'),
        ]);

        // ✅ Attacher automatiquement toutes les classes
        foreach (Classe::all() as $classe) {

            AnneeClasse::create([
                'classe_id' => $classe->id,
                'annee_id'  => $annee->id,
                'en_cours'  => true,
            ]);
        }

        // ✅ Copier automatiquement les frais
        // depuis la dernière année existante
        $ancienneAnnee = Annee::where('id', '!=', $annee->id)
                                ->latest('id')
                                ->first();

        if ($ancienneAnnee) {

            $frais = AnneeClasseFrais::where('annee_id', $ancienneAnnee->id)->get();

            foreach ($frais as $fraisItem) {

                // éviter les doublons
                AnneeClasseFrais::firstOrCreate([
                    'annee_id' => $annee->id,
                    'classe_id' => $fraisItem->classe_id,
                    'frais_id' => $fraisItem->frais_id,
                ], [
                    'montant' => $fraisItem->montant,
                ]);
            }
        }
    });

    return redirect()
        ->route('annees.index')
        ->with('success', 'Année scolaire créée avec classes et frais copiés automatiquement.');
}
    /**
     * Affiche les détails d'une année.
     */
    public function show(Annee $annee)
    {
        return view('annees.show', compact('annee'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(Annee $annee)
    {
        return view('annees.edit', compact('annee'));
    }

    /**
     * Met à jour une année scolaire.
     */
   public function update(Request $request, Annee $annee)
{
    $request->validate([
        'nom'       => 'required|string|unique:annees,nom,' . $annee->id,
        'debut'     => 'required|date',
        'fin'       => 'required|date|after:debut',
        'en_cours'  => 'nullable|boolean',
    ]);

    DB::transaction(function () use ($request, $annee) {

        // ✅ Une seule année en cours
        if ($request->boolean('en_cours')) {

            Annee::where('en_cours', true)
                ->where('id', '!=', $annee->id)
                ->update(['en_cours' => false]);
        }

        // ✅ Mise à jour de l’année
        $annee->update([
            'nom'       => $request->nom,
            'debut'     => $request->debut,
            'fin'       => $request->fin,
            'en_cours'  => $request->boolean('en_cours'),
        ]);

        // ✅ Attacher automatiquement les nouvelles classes manquantes
        foreach (Classe::all() as $classe) {

            AnneeClasse::firstOrCreate([
                'classe_id' => $classe->id,
                'annee_id'  => $annee->id,
            ], [
                'en_cours' => true,
            ]);
        }

        // ✅ Copier les frais manquants depuis une autre année
        $ancienneAnnee = Annee::where('id', '!=', $annee->id)
                                ->latest('id')
                                ->first();

        if ($ancienneAnnee) {

            $frais = AnneeClasseFrais::where('annee_id', $ancienneAnnee->id)->get();

            foreach ($frais as $fraisItem) {

                AnneeClasseFrais::firstOrCreate([
                    'annee_id' => $annee->id,
                    'classe_id' => $fraisItem->classe_id,
                    'frais_id' => $fraisItem->frais_id,
                ], [
                    'montant' => $fraisItem->montant,
                ]);
            }
        }
    });

    return redirect()
        ->route('annees.index')
        ->with('success', 'Année mise à jour avec succès.');
}
    /**
     * Supprime une année scolaire.
     */
    public function destroy(Annee $annee)
    {
        $annee->delete();
        return redirect()->route('annees.index')->with('success', 'Année supprimée avec succès.');
    }

    public function getClassesActives($anneeId)
{
    $classes = \App\Models\Classe::orderBy('id')->join('annee_classe', 'classes.id', '=', 'annee_classe.classe_id')
                ->where('annee_classe.annee_id', $anneeId)
                ->where('annee_classe.active', true)
                ->select('classes.id', 'classes.nom', 'classes.niveau', 'annee_classe.active')
                ->get();

    return response()->json($classes);
}
public function getClassesCycle3(Annee $annee)
{
    $classes = Classe::where('cycle_id', 3)
        ->whereHas('annees', function($q) use ($annee) {
            $q->where('annee_id', $annee->id);
        })
        ->orderBy('ordre')
        ->get(['id', 'nom', 'niveau', 'cycle_id']);

    return response()->json($classes);
}


public function copierFraisDepuisAnnee($ancienneAnneeId, $nouvelleAnneeId)
{
    $frais = AnneeClasseFrais::where('annee_id', $ancienneAnneeId)->get();

    foreach ($frais as $fraisItem) {

        // éviter doublons
        AnneeClasseFrais::firstOrCreate([
            'annee_id' => $nouvelleAnneeId,
            'classe_id' => $fraisItem->classe_id,
            'frais_id' => $fraisItem->frais_id,
        ], [
            'montant' => $fraisItem->montant,
        ]);
    }
}


}
