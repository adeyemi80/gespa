<?php

namespace App\Http\Controllers;

use App\Models\TypeRecette;
use App\Models\CategorieRecette;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypeRecetteController extends Controller
{
    public function index()
    {
        $typesRecettes = TypeRecette::with('categorieRecette')
            ->orderBy('nom')
            ->get();

        return view('types_recettes.index', compact('typesRecettes'));
    }

    public function create()
    {
        $categories = CategorieRecette::orderBy('nom')->get();

        return view('types_recettes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categories_recettes_id' => [
                'required',
                'exists:categories_recettes,id',
            ],

            'nom' => [
                'required',
                'string',
                'max:150',

                Rule::unique('types_recettes', 'nom')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'categories_recettes_id',
                            $request->categories_recettes_id
                        );
                    }),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['actif'] = $request->boolean('actif');

        TypeRecette::create($validated);

        return redirect()
            ->route('types-recettes.index')
            ->with('success', 'Type de recette ajouté avec succès.');
    }

    public function show(TypeRecette $typesRecette)
    {
        $typesRecette->load('categorieRecette');

        return view(
            'types_recettes.show',
            compact('typesRecette')
        );
    }

    public function edit(TypeRecette $typesRecette)
    {
        $categories = CategorieRecette::orderBy('nom')->get();

        return view(
            'types_recettes.edit',
            compact('typesRecette', 'categories')
        );
    }

    public function update(
        Request $request,
        TypeRecette $typesRecette
    ) {
        $validated = $request->validate([
            'categories_recettes_id' => [
                'required',
                'exists:categories_recettes,id',
            ],

            'nom' => [
                'required',
                'string',
                'max:150',

                Rule::unique('types_recettes', 'nom')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'categories_recettes_id',
                            $request->categories_recettes_id
                        );
                    })
                    ->ignore($typesRecette->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['actif'] = $request->boolean('actif');

        $typesRecette->update($validated);

        return redirect()
            ->route('types-recettes.index')
            ->with('success', 'Type de recette modifié avec succès.');
    }

    public function destroy(TypeRecette $typesRecette)
    {
        $typesRecette->delete();

        return redirect()
            ->route('types-recettes.index')
            ->with('success', 'Type de recette supprimé avec succès.');
    }
}