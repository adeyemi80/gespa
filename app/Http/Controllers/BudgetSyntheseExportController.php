<?php
// app/Http/Controllers/BudgetSyntheseExportController.php

namespace App\Http\Controllers;

use App\Services\BudgetSyntheseService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BudgetSyntheseExportController extends Controller
{
    public function pdf(Request $request, BudgetSyntheseService $service): Response
    {
        $anneeId = $request->integer('annee_id')
    ?: Annee::where('en_cours', true)->value('id')  // ⚠️ adapte le nom de colonne
    ?: Annee::orderByDesc('id')->value('id');

        $donnees = $service->getDonnees($anneeId);

        $pdf = Pdf::loadView('pdf.budget-synthese', $donnees)
            ->setPaper('a4', 'portrait');

        $nomFichier = 'budget-synthese-' . str_replace(['/', ' '], '-', $donnees['anneeLibelle']) . '.pdf';

        return $pdf->download($nomFichier);
    }
}