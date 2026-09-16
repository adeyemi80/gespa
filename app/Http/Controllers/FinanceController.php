<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Recette;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
{
    // Années scolaires
    $annees = Annee::orderByDesc('id')->get();

    // Année sélectionnée ou année en cours par défaut
    $anneeId = $request->get(
        'annee_id',
        optional(Annee::where('en_cours', true)->first())->id
    );

    // =========================
    // RECETTES
    // =========================

    $recettesQuery = Recette::query();

    if ($anneeId) {
        $recettesQuery->where('annee_id', $anneeId);
    }

    $recettesParJour = (clone $recettesQuery)
        ->select(
            DB::raw('DATE(date_paiement) as jour'),
            DB::raw('SUM(montant_verse) as total')
        )
        ->groupBy('jour')
        ->orderByDesc('jour')
        ->get();

    $recettesParMois = (clone $recettesQuery)
        ->select(
            DB::raw('EXTRACT(YEAR FROM date_paiement) AS annee'),
            DB::raw('EXTRACT(MONTH FROM date_paiement) AS mois'),
            DB::raw('SUM(montant_verse) AS total')
        )
        ->groupBy('annee', 'mois')
        ->orderByDesc('annee')
        ->orderByDesc('mois')
        ->get();

    $recettesParAnnee = (clone $recettesQuery)
        ->select(
            DB::raw('EXTRACT(YEAR FROM date_paiement) AS annee'),
            DB::raw('SUM(montant_verse) AS total')
        )
        ->groupBy('annee')
        ->orderByDesc('annee')
        ->get();

    // =========================
    // DEPENSES
    // =========================

    $depensesQuery = Depense::query();

    if ($anneeId) {
        $depensesQuery->where('annee_id', $anneeId);
    }

    $depensesParJour = (clone $depensesQuery)
        ->select(
            DB::raw('DATE(date_depense) as jour'),
            DB::raw('SUM(montant) as total')
        )
        ->groupBy('jour')
        ->orderByDesc('jour')
        ->get();

    $depensesParMois = (clone $depensesQuery)
        ->select(
            DB::raw('EXTRACT(YEAR FROM date_depense) AS annee'),
            DB::raw('EXTRACT(MONTH FROM date_depense) AS mois'),
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('annee', 'mois')
        ->orderByDesc('annee')
        ->orderByDesc('mois')
        ->get();

    $depensesParAnnee = (clone $depensesQuery)
        ->select(
            DB::raw('EXTRACT(YEAR FROM date_depense) AS annee'),
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('annee')
        ->orderByDesc('annee')
        ->get();

    // =========================
    // TOTAUX DE L'ANNÉE
    // =========================

    $totalRecettes = (clone $recettesQuery)->sum('montant_verse');

    $totalDepenses = (clone $depensesQuery)->sum('montant');

    $solde = $totalRecettes - $totalDepenses;

    // Année sélectionnée
    $anneeSelectionnee = $annees->firstWhere('id', $anneeId);

    return view('finances.index', compact(
        'annees',
        'anneeId',
        'anneeSelectionnee',

        'recettesParJour',
        'recettesParMois',
        'recettesParAnnee',

        'depensesParJour',
        'depensesParMois',
        'depensesParAnnee',

        'totalRecettes',
        'totalDepenses',
        'solde'
    ));
}

   
}
