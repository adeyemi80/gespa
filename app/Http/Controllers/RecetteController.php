<?php
// app/Http/Controllers/RecetteController.php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\CategorieRecette;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\Recette;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RecetteController extends Controller
{
    /**
     * Affiche la liste des recettes.
     */
    public function index(Request $request): View
    {
        $recettes = Recette::query()
            ->with(['categorieRecette', 'annee', 'inscription', 'paiement'])
            ->when($request->filled('annee_id'), fn ($q) => $q->where('annee_id', $request->annee_id))
            ->when($request->filled('categorie_recette_id'), fn ($q) => $q->where('categorie_recette_id', $request->categorie_recette_id))
            ->orderByDesc('date_paiement')
            ->paginate(20)
            ->withQueryString();

        $annees = Annee::orderByDesc('id')->get();
        $categories = CategorieRecette::orderBy('nom')->get(); // ⚠️ adapte le nom de colonne si besoin

        return view('recettes.index', compact('recettes', 'annees', 'categories'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $annees = Annee::orderByDesc('id')->get();
        $categories = CategorieRecette::orderBy('nom')->get();
        $inscriptions = Inscription::orderBy('id')->get(); // ⚠️ adapte selon ton besoin (probablement une recherche/select2 plutôt qu'un get() complet)
        $paiements = Paiement::orderBy('id')->get(); // idem

        return view('recettes.create', compact('annees', 'categories', 'inscriptions', 'paiements'));
    }

    /**
     * Enregistre une nouvelle recette.
     */
    

    /**
     * Affiche une recette.
     */
    public function show(Recette $recette): View
    {
        $recette->load(['categorieRecette', 'annee', 'inscription', 'paiement']);

        return view('recettes.show', compact('recette'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Recette $recette): View
    {
        $annees = Annee::orderByDesc('id')->get();
        $categories = CategorieRecette::orderBy('nom')->get();
        $inscriptions = Inscription::orderBy('id')->get();
        $paiements = Paiement::orderBy('id')->get();

        return view('recettes.edit', compact('recette', 'annees', 'categories', 'inscriptions', 'paiements'));
    }

    /**
     * Met à jour une recette.
     */
    public function update(Request $request, Recette $recette): RedirectResponse
    {
        $validated = $this->validateRecette($request, $recette->id);

        $recette->update($validated);

        return redirect()
            ->route('recettes.index')
            ->with('success', 'Recette mise à jour avec succès.');
    }

    /**
     * Supprime une recette.
     */
    public function destroy(Recette $recette): RedirectResponse
    {
        $recette->delete();

        return redirect()
            ->route('recettes.index')
            ->with('success', 'Recette supprimée avec succès.');
    }

    /**
     * Règles de validation communes à store() et update().
     */
   
}