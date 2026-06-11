<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Inscription;
use Illuminate\Http\Request;

class RecetteController extends Controller
{
    /**
     * Affiche la liste des recettes
     */
    public function index(Request $request)
{
    $classes = Classe::all();
    $annees = Annee::all();

    // On récupère les recettes avec les relations nécessaires
    $recettes = Recette::with('paiement.inscription.eleve', 'paiement.inscription.classe')
        ->latest()
        ->paginate(10);

    return view('recettes.index', compact('recettes', 'classes', 'annees'));
}

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('recettes.create');
    }

    /**
     * Enregistre une nouvelle recette
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'date_paiement' => 'required|date',
            'libelle' => 'string|max:255',
             'montant_verse' => 'required|numeric|min:0|max:99999999999999999999,99',
        ]);

        Recette::create($validated);

        return redirect()->route('recettes.index')
            ->with('success', 'Recette ajoutée avec succès.');
    }

    /**
     * Affiche une recette
     */
    public function show(Recette $recette)
    {
        return view('recettes.show', compact('recette'));
    }

    /**
     * Affiche le formulaire d’édition
     */
    public function edit(Recette $recette)
    {
        return view('recettes.edit', compact('recette'));
    }

    /**
     * Met à jour une recette
     */
    public function update(Request $request, Recette $recette)
    {
        $validated = $request->validate([
            'date_paiement' => 'required|date',
            'montant_verse' => 'required|numeric|min:0|max:99999999999999999999,99',
        ]);

        $recette->update($validated);

        return redirect()->route('recettes.index')
            ->with('success', 'Recette mise à jour avec succès.');
    }

    /**
     * Supprime une recette
     */
    public function destroy(Recette $recette)
    {
        $recette->delete();

        return redirect()->route('recettes.index')
            ->with('success', 'Recette supprimée avec succès.');
    }
}
