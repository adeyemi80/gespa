<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\BudgetDepense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BudgetDepensePdfController extends Controller
{
    public function export(Request $request): Response
    {
        $request->validate([
            'annee_id' => 'required|exists:annees,id',
        ]);

        $annee = Annee::findOrFail($request->annee_id);

        $budgets = BudgetDepense::query()
            ->with('categorie')
            ->where('annee_id', $annee->id)
            ->get()
            ->sortBy(fn ($budget) => $budget->categorie->nom)
            ->values();

        $totalAlloue = $budgets->sum('montant_alloue');
        $totalUtilise = $budgets->sum('montant_utilise');
        $totalRestant = $totalAlloue - $totalUtilise;
        $tauxGlobal = $totalAlloue > 0 ? round(($totalUtilise / $totalAlloue) * 100, 2) : 0;

        $pdf = Pdf::loadView('depenses.pdf.budget', [
            'annee' => $annee,
            'budgets' => $budgets,
            'totalAlloue' => $totalAlloue,
            'totalUtilise' => $totalUtilise,
            'totalRestant' => $totalRestant,
            'tauxGlobal' => $tauxGlobal,
            'dateGeneration' => now(),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $nomFichier = 'budget-depenses-' . str_replace([' ', '/'], '-', $annee->nom) . '.pdf';

        return $pdf->stream($nomFichier);
    }
}