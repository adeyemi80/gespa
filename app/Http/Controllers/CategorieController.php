<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:recette,dépense',
            'description' => 'nullable|string',
        ]);

        Categorie::create($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function show(Categorie $categorie)
    {
        return view('categories.show', compact('categorie'));
    }

    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:recette,dépense',
            'description' => 'nullable|string',
        ]);

        $categorie->update($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie supprimée.');
    }
}
