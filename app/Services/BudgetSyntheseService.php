<?php
// app/Services/BudgetSyntheseService.php

namespace App\Services;

use App\Models\Annee;
use App\Models\BudgetDepense;
use App\Models\BudgetRecette;
use App\Models\Cycle;
use App\Models\Recette;
use App\Models\Depense;
use Illuminate\Support\Facades\DB;

class BudgetSyntheseService
{
    public function getDonnees(?int $anneeId): array
    {
        $annees = Annee::orderByDesc('id')->get();
        $cycles = Cycle::orderBy('ordre')->get();

        // --- Recettes : ventilation par cycle via inscription->classe->cycle_id ---
        $recettesParCycle = Recette::query()
            ->join('inscriptions', 'inscriptions.id', '=', 'recettes.inscription_id')
            ->join('classes', 'classes.id', '=', 'inscriptions.classe_id')
            ->when($anneeId, fn ($q) => $q->where('recettes.annee_id', $anneeId))
            ->select('recettes.categorie_recette_id', 'classes.cycle_id', DB::raw('SUM(recettes.montant_verse) as total'))
            ->groupBy('recettes.categorie_recette_id', 'classes.cycle_id')
            ->get()
            ->groupBy('categorie_recette_id');

        $budgetsRecettes = BudgetRecette::query()
            ->with('categorie')
            ->when($anneeId, fn ($q) => $q->where('annee_id', $anneeId))
            ->get()
            ->map(function ($b) use ($recettesParCycle, $cycles) {
                $ventilation = $recettesParCycle->get($b->categorie_id, collect())
                    ->keyBy('cycle_id');

                $parCycle = $cycles->mapWithKeys(fn ($cycle) => [
                    $cycle->nom => (float) ($ventilation->get($cycle->id)->total ?? 0),
                ]);

                return [
                    'code'      => $b->categorie->code,
                    'nom'       => $b->categorie->nom,
                    'prevu'     => (float) $b->montant_prevu,
                    'realise'   => (float) $b->montant_realise,
                    'ecart'     => (float) $b->montant_realise - (float) $b->montant_prevu,
                    'taux'      => $b->montant_prevu > 0
                        ? round(((float) $b->montant_realise / (float) $b->montant_prevu) * 100, 2)
                        : 0,
                    'par_cycle' => $parCycle,
                ];
            })
            ->sortBy('nom')
            ->values();

        // --- Dépenses : ventilation par cycle via depenses.cycle_id (saisi manuellement) ---
        $depensesParCycle = Depense::query()
            ->join('types_depenses', 'types_depenses.id', '=', 'depenses.type_depense_id')
            ->when($anneeId, fn ($q) => $q->where('depenses.annee_id', $anneeId))
            ->select('types_depenses.categorie_id', 'depenses.cycle_id', DB::raw('SUM(depenses.montant) as total'))
            ->groupBy('types_depenses.categorie_id', 'depenses.cycle_id')
            ->get()
            ->groupBy('categorie_id');

        $budgetsDepenses = BudgetDepense::query()
            ->with('categorie')
            ->when($anneeId, fn ($q) => $q->where('annee_id', $anneeId))
            ->get()
            ->map(function ($b) use ($depensesParCycle, $cycles) {
                $ventilation = $depensesParCycle->get($b->categorie_id, collect())
                    ->keyBy('cycle_id');

                $parCycle = $cycles->mapWithKeys(fn ($cycle) => [
                    $cycle->nom => (float) ($ventilation->get($cycle->id)->total ?? 0),
                ]);

                return [
                    'code'      => $b->categorie->code,
                    'nom'       => $b->categorie->nom,
                    'alloue'    => (float) $b->montant_alloue,
                    'utilise'   => (float) $b->montant_utilise,
                    'restant'   => (float) $b->montant_alloue - (float) $b->montant_utilise,
                    'taux'      => $b->montant_alloue > 0
                        ? round(((float) $b->montant_utilise / (float) $b->montant_alloue) * 100, 2)
                        : 0,
                    'par_cycle' => $parCycle,
                ];
            })
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

        // Totaux "Réalisé"/"Utilisé" par cycle, tous catégories confondues
        $totauxRecettesParCycle = $cycles->mapWithKeys(fn ($cycle) => [
            $cycle->nom => $budgetsRecettes->sum(fn ($b) => $b['par_cycle'][$cycle->nom] ?? 0),
        ]);

        $totauxDepensesParCycle = $cycles->mapWithKeys(fn ($cycle) => [
            $cycle->nom => $budgetsDepenses->sum(fn ($b) => $b['par_cycle'][$cycle->nom] ?? 0),
        ]);

        return [
            'annees'                 => $annees,
            'cycles'                 => $cycles,
            'anneeLibelle'           => $annees->firstWhere('id', $anneeId)?->nom ?? 'Toutes années',
            'budgetsRecettes'        => $budgetsRecettes,
            'budgetsDepenses'        => $budgetsDepenses,
            'totalPrevu'             => $totalPrevu,
            'totalRealise'           => $totalRealise,
            'totalEcart'             => $totalEcart,
            'tauxRecettes'           => $tauxRecettes,
            'totalAlloue'            => $totalAlloue,
            'totalUtilise'           => $totalUtilise,
            'totalRestant'           => $totalRestant,
            'tauxDepenses'           => $tauxDepenses,
            'soldePrevu'             => $totalPrevu - $totalAlloue,
            'soldeRealise'           => $totalRealise - $totalUtilise,
            'totauxRecettesParCycle' => $totauxRecettesParCycle,
            'totauxDepensesParCycle' => $totauxDepensesParCycle,
        ];
    }
}