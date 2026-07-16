<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recette;
use App\Models\Depense;
use DB;

class FinanceController extends Controller
{
    public function index()
    {
        // Recettes
        $recettesParJour = Recette::select(DB::raw('DATE(date_paiement) as jour'), DB::raw('SUM(montant_verse) as total'))
            ->groupBy('jour')
            ->orderByDesc('jour')
            ->get();

        $recettesParMois = Recette::select(
                DB::raw('EXTRACT(YEAR FROM date_paiement) AS annee'),
                DB::raw('EXTRACT(MONTH FROM date_paiement) AS mois'),
                DB::raw('SUM(montant_verse) AS total')
            )
            ->groupBy('annee', 'mois')
            ->orderByDesc('annee')
            ->orderByDesc('mois')
            ->get();

        $recettesParAnnee = Recette::select(
                DB::raw('EXTRACT(YEAR FROM date_paiement) AS annee'),
                DB::raw('SUM(montant_verse) AS total')
            )
            ->groupBy('annee')
            ->orderByDesc('annee')
            ->get();

        // Dépenses
        $depensesParJour = Depense::select(DB::raw('DATE(date_depense) as jour'), DB::raw('SUM(montant) as total'))
            ->groupBy('jour')
            ->orderByDesc('jour')
            ->get();

        $depensesParMois = Depense::select(
                DB::raw('EXTRACT(YEAR FROM date_depense) AS annee'),
                DB::raw('EXTRACT(MONTH FROM date_depense) AS mois'),
                DB::raw('SUM(montant) AS total')
            )
            ->groupBy('annee', 'mois')
            ->orderByDesc('annee')
            ->orderByDesc('mois')
            ->get();

        $depensesParAnnee = Depense::select(
                DB::raw('EXTRACT(YEAR FROM date_depense) AS annee'),
                DB::raw('SUM(montant) AS total')
            )
            ->groupBy('annee')
            ->orderByDesc('annee')
            ->get();

        // Solde global
        $totalRecettes = Recette::sum('montant_verse');
        $totalDepenses = Depense::sum('montant');
        $solde = $totalRecettes - $totalDepenses;

        return view('finances.index', compact(
            'recettesParJour', 'recettesParMois', 'recettesParAnnee',
            'depensesParJour', 'depensesParMois', 'depensesParAnnee',
            'totalRecettes', 'totalDepenses', 'solde'
        ));
    }

   
}
