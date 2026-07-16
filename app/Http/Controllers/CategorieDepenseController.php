<?php

namespace App\Http\Controllers;

use App\Models\CategorieDepense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategorieDepenseController extends Controller
{
    public function index(Request $request): View
    {
        $categories = CategorieDepense::query()
            ->when($request->filled('recherche'), function ($query) use ($request) {
                $query->where('nom', 'ILIKE', '%' . $request->recherche . '%')
                    ->orWhere('code', 'ILIKE', '%' . $request->recherche . '%');
            })
            ->orderBy('nom')
            ->paginate(20)
            ->withQueryString();

        return view('depenses.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('depenses.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $valide = $this->validerDonnees($request);

        CategorieDepense::create($valide);

        return redirect()
            ->route('categories-depenses.create')
            ->with('succes', 'Catégorie de dépense créée avec succès.');
    }

    public function show(CategorieDepense $categoriesDepense): View
    {
        $categoriesDepense->load('typesDepenses');

        return view('depenses.categories.show', ['categorie' => $categoriesDepense]);
    }

    public function edit(CategorieDepense $categoriesDepense): View
    {
        return view('depenses.categories.edit', ['categorie' => $categoriesDepense]);
    }

    public function update(Request $request, CategorieDepense $categoriesDepense): RedirectResponse
    {
        $valide = $this->validerDonnees($request, $categoriesDepense->id);

        $categoriesDepense->update($valide);

        return redirect()
            ->route('categories-depenses.index')
            ->with('succes', 'Catégorie de dépense mise à jour avec succès.');
    }

    public function destroy(CategorieDepense $categoriesDepense): RedirectResponse
    {
        if ($categoriesDepense->typesDepenses()->exists()) {
            return redirect()
                ->route('categories-depenses.index')
                ->with('erreur', 'Impossible de supprimer : des types de dépenses sont rattachés à cette catégorie.');
        }

        $categoriesDepense->delete();

        return redirect()
            ->route('categories-depenses.index')
            ->with('succes', 'Catégorie de dépense supprimée avec succès.');
    }

    protected function validerDonnees(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:10|unique:categories_depenses,code' . ($id ? ",$id" : ''),
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'actif' => 'boolean',
        ]);
    }
}