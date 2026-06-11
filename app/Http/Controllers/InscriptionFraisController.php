<?php

namespace App\Http\Controllers;

use App\Models\InscriptionFrais;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Annee;
use Illuminate\Http\Request;

class InscriptionFraisController extends Controller
{
    
public function index(Request $request)
{
    $query = InscriptionFrais::with([
        'inscription.eleve',
        'inscription.classe.cycle', // 🔥 important pour éviter N+1
        'frais'
    ]);

    // 🔎 Filtrage global via inscription
    $query->whereHas('inscription', function ($q) use ($request) {

        // 🔎 Filtre classe
        if ($request->filled('classe_id')) {
            $q->where('classe_id', $request->classe_id);
        }

        // 🔎 Filtre élève
        if ($request->filled('eleve_id')) {
            $q->where('eleve_id', $request->eleve_id);
        }

        // 🔎 Filtre cycle (via classe)
        if ($request->filled('cycle_id')) {
            $q->whereHas('classe', function ($q2) use ($request) {
                $q2->where('cycle_id', $request->cycle_id);
            });
        }

    });

    // 🔎 Filtre année (direct)
    if ($request->filled('annee_id')) {
        $query->where('annee_id', $request->annee_id);
    }

    $inscriptionFrais = $query->latest()->paginate(2000);

    return view('inscription_frais.index', [
        'inscriptionFrais' => $inscriptionFrais,
        'classes' => Classe::orderBy('nom')->get(),
        'annees'  => Annee::orderBy('nom')->get(),
        'cycles'  => Cycle::orderBy('nom')->get(),
    ]);
}

    /**
     * Afficher le formulaire de modification du frais d’un élève
     */
    public function edit(InscriptionFrais $inscription_frai)
{
    $inscription_frai->load([
        'frais',
        'annee',
        'inscription.eleve',
        'inscription.classe'
    ]);

    return view('inscription_frais.edit', compact('inscription_frai'));
}


public function show(InscriptionFrais $inscription_frai)
{
    $inscription_frai->load([
        'frais',
        'annee',
        'inscription.eleve',
        'inscription.classe'
    ]);

    return view('inscription_frais.show', compact('inscription_frai'));
}


    /**
     * Mettre à jour le montant du frais pour un élève
     */
   public function update(Request $request, InscriptionFrais $inscription_frai)
{
    $request->validate([
        'montant_frais' => 'required|numeric|min:0',
        'montant_paye'  => 'required|numeric|min:0',
        'statut'        => 'required|in:non_payé,partiellement_payé,soldé',
        'est_arriere'   => 'required|boolean',
    ]);

    // 🔁 Recalcul automatique
    $reste = $request->montant_frais - $request->montant_paye;

    if ($reste <= 0) {
        $statut = 'soldé';
        $reste  = 0;
    } elseif ($request->montant_paye > 0) {
        $statut = 'partiellement_payé';
    } else {
        $statut = 'non_payé';
    }

    $inscription_frai->update([
        'montant_frais' => $request->montant_frais,
        'montant_paye'  => $request->montant_paye,
        'reste'         => $reste,
        'statut'        => $statut,
        'est_arriere'   => $request->est_arriere,
    ]);

    return redirect()
        ->route('inscription-frais.show', $inscription_frai->id)
        ->with('success', '✅ Frais de l’élève modifié avec succès.');
}

public function elevesParClasse($classeId)
{
    $eleves = \App\Models\Eleve::whereHas('inscriptions', function ($q) use ($classeId) {
        $q->where('classe_id', $classeId);
    })
    ->orderBy('nom')        // 🔥 tri alphabétique
    ->orderBy('prenom')     // 🔥 pour affiner
    ->get(['id', 'nom', 'prenom']);

    return response()->json($eleves);
}

}
