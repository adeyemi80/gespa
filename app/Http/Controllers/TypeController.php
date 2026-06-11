<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    /**
     * Afficher la liste des types
     */
    public function index()
    {
        $types = Type::orderBy('nom')->get();
        return view('types.index', compact('types'));
    }

    /**
     * Afficher le formulaire pour créer un nouveau type
     */
    public function create()
    {
        return view('types.create');
    }

    /**
     * Enregistrer un nouveau type dans la base
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:types,nom',
        ]);

        Type::create([
            'nom' => $request->nom,
        ]);

        return redirect()->route('types.index')
            ->with('success', '✅ Type enregistré avec succès.');
    }

    /**
     * Afficher le formulaire pour éditer un type existant
     */
    public function edit(Type $type)
    {
        return view('types.edit', compact('type'));
    }

    /**
     * Mettre à jour un type existant
     */
    public function update(Request $request, Type $type)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:types,nom,' . $type->id,
        ]);

        $type->update([
            'nom' => $request->nom,
        ]);

        return redirect()->route('types.index')
            ->with('success', '✅ Type mis à jour avec succès.');
    }

    /**
     * Supprimer un type
     */
    public function destroy(Type $type)
    {
        // Vérifier si le type est utilisé dans des articles
        if ($type->articles()->exists()) {
            return redirect()->route('types.index')
                ->with('error', "⚠️ Impossible de supprimer ce type, il est utilisé par des articles.");
        }

        $type->delete();

        return redirect()->route('types.index')
            ->with('success', '✅ Type supprimé avec succès.');
    }
}
