<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleApiController extends Controller
{
    /**
     * Liste des cycles
     */
    public function index()
    {
        $cycles = Cycle::orderBy('ordre')->get();

        return response()->json([
            'success' => true,
            'data' => $cycles
        ]);
    }

    /**
     * Enregistrer un nouveau cycle
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:cycles,nom',
            'ordre' => 'nullable|integer'
        ]);

        $cycle = Cycle::create([
            'nom' => $validated['nom'],
            'ordre' => $validated['ordre'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cycle créé avec succès',
            'data' => $cycle
        ], 201);
    }

    /**
     * Afficher un cycle
     */
    public function show(Cycle $cycle)
    {
        return response()->json([
            'success' => true,
            'data' => $cycle
        ]);
    }

    /**
     * Mettre à jour un cycle
     */
    public function update(Request $request, Cycle $cycle)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:cycles,nom,' . $cycle->id,
            'ordre' => 'nullable|integer'
        ]);

        $cycle->update([
            'nom' => $validated['nom'],
            'ordre' => $validated['ordre'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cycle mis à jour avec succès',
            'data' => $cycle
        ]);
    }

    /**
     * Supprimer un cycle
     */
    public function destroy(Cycle $cycle)
    {
        // Vérifier s'il est utilisé
        if ($cycle->classes()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer ce cycle car il est lié à des classes'
            ], 400);
        }

        $cycle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cycle supprimé avec succès'
        ]);
    }
}