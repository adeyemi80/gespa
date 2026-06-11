<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    /**
     * Afficher la liste des dépenses.
     */
    public function index()
    {
        $depenses = Depense::orderBy('date', 'desc')->paginate(10);
        return view('depenses.index', compact('depenses'));
    }

    /**
     * Afficher le formulaire de création d’une dépense.
     */
    public function create()
    {
        return view('depenses.create');
    }

    /**
     * Enregistrer une nouvelle dépense.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'      => 'required|date',
            'libelle'   => 'required|string|max:255',
             'montant' => 'required|numeric|min:0|max:99999999999999999999,99',
            'categorie' => 'nullable|string|max:255',
            'description'   => 'string|max:255',
        ]);

        Depense::create($validated);

        return redirect()->route('depenses.create')
            ->with('success', 'Dépense enregistrée avec succès ✅');
    }

    /**
     * Afficher une dépense précise.
     */
    public function show(Depense $depense)
    {
        return view('depenses.show', compact('depense'));
    }

    /**
     * Afficher le formulaire d’édition d’une dépense.
     */
    public function edit(Depense $depense)
    {
        return view('depenses.edit', compact('depense'));
    }

    /**
     * Mettre à jour une dépense.
     */
    public function update(Request $request, Depense $depense)
    {
        $validated = $request->validate([
            'date'      => 'required|date',
            'libelle'   => 'required|string|max:255',
            'montant'   => 'required|numeric|min:0',
           'montant' => 'required|numeric|min:0|max:99999999999999999999,99',
           'description'   => 'string|max:255',
        ]);

        $depense->update($validated);

        return redirect()->route('depenses.index')
            ->with('success', 'Dépense mise à jour avec succès ✏️');
    }

    /**
     * Supprimer une dépense.
     */
    public function destroy(Depense $depense)
    {
        $depense->delete();

        return redirect()->route('depenses.index')
            ->with('success', 'Dépense supprimée avec succès 🗑️');
    }
}
