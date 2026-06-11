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
            ->orderBy('jour')
            ->get();

        $recettesParMois = Recette::select(
                DB::raw('EXTRACT(YEAR FROM date_paiement) AS annee'),
                DB::raw('EXTRACT(MONTH FROM date_paiement) AS mois'),
                DB::raw('SUM(montant_verse) AS total')
            )
            ->groupBy('annee', 'mois')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get();

        $recettesParAnnee = Recette::select(
                DB::raw('EXTRACT(YEAR FROM date_paiement) AS annee'),
                DB::raw('SUM(montant_verse) AS total')
            )
            ->groupBy('annee')
            ->orderBy('annee')
            ->get();

        // Dépenses
        $depensesParJour = Depense::select(DB::raw('DATE(date) as jour'), DB::raw('SUM(montant) as total'))
            ->groupBy('jour')
            ->orderBy('jour')
            ->get();

        $depensesParMois = Depense::select(
                DB::raw('EXTRACT(YEAR FROM date) AS annee'),
                DB::raw('EXTRACT(MONTH FROM date) AS mois'),
                DB::raw('SUM(montant) AS total')
            )
            ->groupBy('annee', 'mois')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get();

        $depensesParAnnee = Depense::select(
                DB::raw('EXTRACT(YEAR FROM date) AS annee'),
                DB::raw('SUM(montant) AS total')
            )
            ->groupBy('annee')
            ->orderBy('annee')
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

    // Optionnel : ajouter des recettes
    public function storeRecette(Request $request)
    {
        $request->validate([
            'date_paiement' => 'required|date',
            'libelle' => 'required|string|max:255',
             'montant_verse' => 'required|numeric|min:0|max:99999999999999999999,99',
        ]);

        Recette::create($request->all());
        return back()->with('success', 'Recette ajoutée');
    }

    // Optionnel : ajouter des dépenses
    public function storeDepense(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0|max:99999999999999999999,99',
        ]);

        Depense::create($request->all());
        return back()->with('success', 'Dépense ajoutée');
    }
}
