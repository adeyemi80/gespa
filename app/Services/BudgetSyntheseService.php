<?php
// app/Services/BudgetSyntheseService.php

namespace App\Services;

use App\Models\Annee;
use App\Models\BudgetDepense;
use App\Models\BudgetRecette;

class BudgetSyntheseService
{
    public function getDonnees(?int $anneeId): array
    {
        $annees = Annee::orderByDesc('id')->get();

        $budgetsRecettes = BudgetRecette::query()
            ->with('categorie')
            ->when($anneeId, fn ($q) => $q->where('annee_id', $anneeId))
            ->get()
            ->map(fn ($b) => [
                'code'    => $b->categorie->code,
                'nom'     => $b->categorie->nom,
                'prevu'   => (float) $b->montant_prevu,
                'realise' => (float) $b->montant_realise,
                'ecart'   => (float) $b->montant_realise - (float) $b->montant_prevu,
                'taux'    => $b->montant_prevu > 0
                    ? round(((float) $b->montant_realise / (float) $b->montant_prevu) * 100, 2)
                    : 0,
            ])
            ->sortBy('nom')
            ->values();

        $budgetsDepenses = BudgetDepense::query()
            ->with('categorie')
            ->when($anneeId, fn ($q) => $q->where('annee_id', $anneeId))
            ->get()
            ->map(fn ($b) => [
                'code'    => $b->categorie->code,
                'nom'     => $b->categorie->nom,
                'alloue'  => (float) $b->montant_alloue,
                'utilise' => (float) $b->montant_utilise,
                'restant' => (float) $b->montant_alloue - (float) $b->montant_utilise,
                'taux'    => $b->montant_alloue > 0
                    ? round(((float) $b->montant_utilise / (float) $b->montant_alloue) * 100, 2)
                    : 0,
            ])
            ->sortBy('nom')
            ->values();

        $totalPrevu   = $budgetsRecettes->sum('prevu');
        $totalRealise = $budgetsRecettes->sum('realise');
        $totalEcart   = $totalRealise - $totalPrevu;
        $tauxRecettes = $totalPrevu > 0 ? round(($totalRealise / $totalPrevu) * 100, 2) : 0;

        $totalAlloue  = $budgetsDepenses->sum('alloue');
        $totalUtilise = $budgetsDepenses->sum('utilise');
        $totalRestant = $totalAlloue - $totalUtilise;
        $tauxDepenses = $totalAlloue > 0 ? round(($totalUtilise / $totalAlloue) * 100, 2) : 0;

        return [
            'annees'          => $annees,
            'anneeLibelle'    => $annees->firstWhere('id', $anneeId)?->nom ?? 'Toutes années',
            'budgetsRecettes' => $budgetsRecettes,
            'budgetsDepenses' => $budgetsDepenses,
            'totalPrevu'      => $totalPrevu,
            'totalRealise'    => $totalRealise,
            'totalEcart'      => $totalEcart,
            'tauxRecettes'    => $tauxRecettes,
            'totalAlloue'     => $totalAlloue,
            'totalUtilise'    => $totalUtilise,
            'totalRestant'    => $totalRestant,
            'tauxDepenses'    => $tauxDepenses,
            'soldePrevu'      => $totalPrevu - $totalAlloue,
            'soldeRealise'    => $totalRealise - $totalUtilise,
        ];
    }
}