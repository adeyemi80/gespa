<?php

namespace App\Http\Controllers;

use App\Models\CategorieRecette;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategorieRecetteController extends Controller
{
    public function index(Request $request): View
    {
        $categories = CategorieRecette::query()
            ->when($request->filled('recherche'), function ($query) use ($request) {
                $query->where('nom', 'ILIKE', '%' . $request->recherche . '%')
                    ->orWhere('code', 'ILIKE', '%' . $request->recherche . '%');
            })
            ->orderBy('nom')
            ->paginate(20)
            ->withQueryString();

        return view('recettes.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('recettes.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $valide = $this->validerDonnees($request);

        CategorieRecette::create($valide);

        return redirect()
            ->route('categories-recettes.index')
            ->with('succes', 'Catégorie de recette créée avec succès.');
    }

    public function show(CategorieRecette $categoriesRecette): View
    {
        $categoriesRecette->load('budgets.annee');

        return view('recettes.categories.show', ['categorie' => $categoriesRecette]);
    }

    public function edit(CategorieRecette $categoriesRecette): View
    {
        return view('recettes.categories.edit', ['categorie' => $categoriesRecette]);
    }

    public function update(Request $request, CategorieRecette $categoriesRecette): RedirectResponse
    {
        $valide = $this->validerDonnees($request, $categoriesRecette->id);

        $categoriesRecette->update($valide);

        return redirect()
            ->route('categories-recettes.index')
            ->with('succes', 'Catégorie de recette mise à jour avec succès.');
    }

    public function destroy(CategorieRecette $categoriesRecette): RedirectResponse
    {
        if ($categoriesRecette->budgets()->exists()) {
            return redirect()
                ->route('categories-recettes.index')
                ->with('erreur', 'Impossible de supprimer : des budgets sont rattachés à cette catégorie.');
        }

        $categoriesRecette->delete();

        return redirect()
            ->route('categories-recettes.index')
            ->with('succes', 'Catégorie de recette supprimée avec succès.');
    }

    protected function validerDonnees(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:10|unique:categories_recettes,code' . ($id ? ",$id" : ''),
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'actif' => 'boolean',
        ]);
    }
}