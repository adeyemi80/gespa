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

        return view('recettes.create', compact('annees', 'categories', 'inscriptions', 'paiements'));
    }

    /**
     * Enregistre une nouvelle recette.
     */
    

    /**
     * Affiche une recette.
     */
    
    /**
     * Formulaire d'édition.
     */


    /**
     * Met à jour une recette.
     */
    
    /**
     * Supprime une recette.
     */
    
    /**
     * Règles de validation communes à store() et update().
     */
    
}