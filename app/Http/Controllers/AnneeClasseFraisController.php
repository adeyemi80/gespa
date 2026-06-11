<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeClasseFrais;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;

class AnneeClasseFraisController extends Controller
{
    public function index(Request $request)
{
    $query = AnneeClasseFrais::with(['annee', 'classe.cycle']);

    // 🔹 FILTRE ANNÉE
    if ($request->filled('annee_id')) {
        $query->where('annee_id', $request->annee_id);
    }

    // 🔹 FILTRE CYCLE (IMPORTANT)
    if ($request->filled('cycle_id')) {
        $query->whereHas('classe', function ($q) use ($request) {
            $q->where('cycle_id', $request->cycle_id);
        });
    }

    // 🔹 FILTRE CLASSE
    if ($request->filled('classe_id')) {
        $query->where('classe_id', $request->classe_id);
    }

    // 🔹 TRI
    $data = $query
        ->orderBy('annee_id')
        ->orderBy('classe_id')
        ->get();

    // 🔹 DONNÉES POUR LES SELECTS
    $annees = Annee::orderBy('nom')->get();
    $cycles = Cycle::orderBy('nom')->get();
    $classes = Classe::orderBy('nom')->get();

    return view('frais.annee_classe_frais.index', compact(
        'data',
        'annees',
        'cycles',
        'classes',
    ));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
        ]);

        $item = AnneeClasseFrais::findOrFail($id);

        $item->update([
            'montant' => $request->montant,
        ]);

        return back()->with('success', 'Montant mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $item = AnneeClasseFrais::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Ligne supprimée avec succès.');
    }
}