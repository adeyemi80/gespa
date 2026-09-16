<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\CategorieRecette;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\PaiementAchat;
use App\Models\Recette;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RecetteController extends Controller
{
    /**
     * Affiche la liste des recettes.
     */
    public function index(Request $request): View
    {
        $recettes = Recette::query()
            ->with([
                'categorieRecette',
                'annee',
                'inscription.eleve',
                'paiement',
                'paiementAchat',
            ])
            ->when(
                $request->filled('annee_id'),
                function ($q) use ($request) {
                    $q->where('annee_id', $request->annee_id);
                }
            )
            ->when(
                $request->filled('categorie_recette_id'),
                function ($q) use ($request) {
                    $q->where(
                        'categorie_recette_id',
                        $request->categorie_recette_id
                    );
                }
            )
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $annees = Annee::orderByDesc('id')->get();

        $categories = CategorieRecette::orderBy('nom')->get();

        return view(
            'recettes.index',
            compact(
                'recettes',
                'annees',
                'categories'
            )
        );
    }


    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $annees = Annee::orderByDesc('id')->get();

        $categories = CategorieRecette::orderBy('nom')->get();

        $inscriptions = Inscription::with('eleve')
            ->orderByDesc('id')
            ->get();

        $paiements = Paiement::orderByDesc('id')->get();

        return view(
            'recettes.create',
            compact(
                'annees',
                'categories',
                'inscriptions',
                'paiements'
            )
        );
    }


    /**
     * Affiche une recette.
     */
    public function show(Recette $recette): View
    {
        $recette->load([
            'categorieRecette',
            'annee',
            'inscription.eleve',
            'paiement',
            'paiementAchat',
        ]);

        return view(
            'recettes.show',
            compact('recette')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Recette $recette): View
    {
        $recette->load([
            'categorieRecette',
            'annee',
            'inscription.eleve',
            'paiement',
            'paiementAchat',
        ]);

        $annees = Annee::orderByDesc('id')->get();

        $categories = CategorieRecette::orderBy('nom')->get();

        $inscriptions = Inscription::with('eleve')
            ->orderByDesc('id')
            ->get();

        $paiements = Paiement::orderByDesc('id')->get();

        return view(
            'recettes.edit',
            compact(
                'recette',
                'annees',
                'categories',
                'inscriptions',
                'paiements'
            )
        );
    }



    /**
     * Supprime une recette.
     */
    public function destroy(
        Recette $recette
    ): RedirectResponse {

        DB::transaction(function () use ($recette) {

            /*
             * IMPORTANT :
             *
             * Pour l'instant on supprime uniquement
             * la recette.
             *
             * On ne supprime PAS automatiquement
             * le paiement associé.
             */
            $recette->delete();
        });


        return redirect()
            ->route('recettes.index')
            ->with(
                'success',
                'Recette supprimée avec succès.'
            );
    }
}
