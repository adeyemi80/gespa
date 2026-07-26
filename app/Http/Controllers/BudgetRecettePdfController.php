<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\BudgetRecette;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BudgetRecettePdfController extends Controller
{
    public function export(Request $request): Response
    {
        $request->validate([
            'annee_id' => 'required|exists:annees,id',
        ]);

        $annee = Annee::findOrFail($request->annee_id);

        $budgets = BudgetRecette::query()
            ->with('categorie')
            ->where('annee_id', $annee->id)
            ->get()
            ->sortBy(fn ($budget) => $budget->categorie->nom)
            ->values();

        $totalPrevu = $budgets->sum('montant_prevu');
        $totalRealise = $budgets->sum('montant_realise');
        $totalEcart = $totalRealise - $totalPrevu;
        $tauxGlobal = $totalPrevu > 0 ? round(($totalRealise / $totalPrevu) * 100, 2) : 0;

        $pdf = Pdf::loadView('recettes.pdf.budget', [
            'annee' => $annee,
            'budgets' => $budgets,
            'totalPrevu' => $totalPrevu,
            'totalRealise' => $totalRealise,
            'totalEcart' => $totalEcart,
            'tauxGlobal' => $tauxGlobal,
            'dateGeneration' => now(),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $nomFichier = 'budget-recettes-' . str_replace([' ', '/'], '-', $annee->nom) . '.pdf';

        return $pdf->stream($nomFichier);
    }
}
