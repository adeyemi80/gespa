<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Categorie;
use App\Models\Annee;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with(['categorie', 'annee'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        return view('budgets.create', [
            'categories' => Categorie::orderBy('nom')->get(),
            'annees'     => Annee::orderByDesc('nom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:255',
            'categorie_id'  => 'required|exists:categories,id',
            'annee_id'      => 'required|exists:annees,id',
            'montant_prevu' => 'required|numeric|min:0',
            'periode'       => 'required|string|max:255',
        ]);

        Budget::create($validated);

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Budget créé avec succès.');
    }

    public function edit(Budget $budget)
    {
        return view('budgets.edit', [
            'budget'     => $budget,
            'categories' => Categorie::orderBy('nom')->get(),
            'annees'     => Annee::orderByDesc('nom')->get(),
        ]);
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:255',
            'categorie_id'  => 'required|exists:categories,id',
            'annee_id'      => 'required|exists:annees,id',
            'montant_prevu' => 'required|numeric|min:0',
            'periode'       => 'required|string|max:255',
        ]);

        $budget->update($validated);

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Budget mis à jour avec succès.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Budget supprimé avec succès.');
    }
}
