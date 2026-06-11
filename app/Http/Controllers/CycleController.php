<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    /**
     * Afficher la liste des cycles
     */
    public function index()
    {
        $cycles = Cycle::orderBy('ordre')->get();
        return view('cycles.index', compact('cycles'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('cycles.create');
    }

    /**
     * Enregistrer un nouveau cycle
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:cycles,nom',
            'ordre' => 'nullable|integer'
        ]);

        Cycle::create([
            'nom' => $request->nom,
            'ordre' => $request->ordre ?? 0,
        ]);

        return redirect()->route('cycles.index')
            ->with('success', 'Cycle créé avec succès ✅');
    }

    /**
     * Afficher un cycle
     */
    public function show(Cycle $cycle)
    {
        return view('cycles.show', compact('cycle'));
    }

    /**
     * Formulaire d’édition
     */
    public function edit(Cycle $cycle)
    {
        return view('cycles.edit', compact('cycle'));
    }

    /**
     * Mettre à jour un cycle
     */
    public function update(Request $request, Cycle $cycle)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:cycles,nom,' . $cycle->id,
            'ordre' => 'nullable|integer'
        ]);

        $cycle->update([
            'nom' => $request->nom,
            'ordre' => $request->ordre ?? 0,
        ]);

        return redirect()->route('cycles.index')
            ->with('success', 'Cycle mis à jour ✅');
    }

    /**
     * Supprimer un cycle
     */
    public function destroy(Cycle $cycle)
    {
        // Vérifier s'il est utilisé par des classes
        if ($cycle->classes()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce cycle (il est lié à des classes)');
        }

        $cycle->delete();

        return redirect()->route('cycles.index')
            ->with('success', 'Cycle supprimé ✅');
    }
}