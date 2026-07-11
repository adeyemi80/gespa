<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Categorie;
use Illuminate\Http\Request;
use PDF; // barryvdh/laravel-dompdf

class RapportController extends Controller
{
    // Formulaire de recherche
    public function index()
    {
        $categories = Categorie::all();
        return view('rapports.index', compact('categories'));
    }

    // Afficher les résultats filtrés
    public function resultats(Request $request)
    {
        $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'date_debut'   => 'required|date',
            'date_fin'     => 'required|date|after_or_equal:date_debut',
        ]);

        $transactions = Transaction::where('categorie_id', $request->categorie_id)
            ->whereBetween('date_transaction', [$request->date_debut, $request->date_fin])
            ->get();

        $somme = $transactions->sum('montant');

        $categorie = Categorie::find($request->categorie_id);

        session([
            'rapport_transactions' => $transactions,
            'rapport_somme' => $somme,
            'rapport_categorie' => $categorie,
            'rapport_date_debut' => $request->date_debut,
            'rapport_date_fin' => $request->date_fin,
        ]);

        return view('rapports.resultats', compact('transactions', 'somme', 'categorie'));
    }

    // Export PDF
    public function exportPdf()
    {
        $transactions = session('rapport_transactions');
        $somme = session('rapport_somme');
        $categorie = session('rapport_categorie');
        $date_debut = session('rapport_date_debut');
        $date_fin = session('rapport_date_fin');

        $pdf = PDF::loadView('rapports.pdf', compact(
            'transactions',
            'somme',
            'categorie',
            'date_debut',
            'date_fin'
        ));

        return $pdf->download('rapport_'.$categorie->nom.'_'.$date_debut.'_au_'.$date_fin.'.pdf');
    }

    public function globalForm()
{
    return view('rapports.global-form');
}

public function globalResultat(Request $request)
{
    $request->validate([
        'date_debut' => 'required|date',
        'date_fin'   => 'required|date|after_or_equal:date_debut',
    ]);

    $date_debut = $request->date_debut;
    $date_fin   = $request->date_fin;

    $categories = \App\Models\Categorie::with(['transactions' => function($q) use ($date_debut, $date_fin) {
        $q->whereBetween('date_transaction', [$date_debut, $date_fin]);
    }])->get();

    $recap = [];
    $totalRecettes = 0;
    $totalDepenses = 0;

    foreach ($categories as $categorie) {
        $recettes = $categorie->transactions->where('type', 'recette')->sum('montant');
        $depenses = $categorie->transactions->where('type', 'depense')->sum('montant');
        $solde = $recettes - $depenses;

        $recap[] = [
            'categorie' => $categorie->nom,
            'recettes'  => $recettes,
            'depenses'  => $depenses,
            'solde'     => $solde,
        ];

        $totalRecettes += $recettes;
        $totalDepenses += $depenses;
    }

    $soldeGlobal = $totalRecettes - $totalDepenses;

    // session pour PDF
    session([
        'global_date_debut' => $date_debut,
        'global_date_fin'   => $date_fin
    ]);

    return view('rapports.global-resultat', compact('recap', 'date_debut', 'date_fin', 'totalRecettes', 'totalDepenses', 'soldeGlobal'));
}

public function globalPdf()
{
    $date_debut = session('global_date_debut');
    $date_fin   = session('global_date_fin');

    $categories = \App\Models\Categorie::with(['transactions' => function($q) use ($date_debut, $date_fin) {
        $q->whereBetween('date_transaction', [$date_debut, $date_fin]);
    }])->get();

    $recap = [];
    $totalRecettes = 0;
    $totalDepenses = 0;

    foreach ($categories as $categorie) {
        $recettes = $categorie->transactions->where('type', 'recette')->sum('montant');
        $depenses = $categorie->transactions->where('type', 'depense')->sum('montant');
        $solde = $recettes - $depenses;

        $recap[] = [
            'categorie' => $categorie->nom,
            'recettes'  => $recettes,
            'depenses'  => $depenses,
            'solde'     => $solde,
        ];

        $totalRecettes += $recettes;
        $totalDepenses += $depenses;
    }

    $soldeGlobal = $totalRecettes - $totalDepenses;

    $pdf = \PDF::loadView('rapports.global-pdf', compact('recap', 'date_debut', 'date_fin', 'totalRecettes', 'totalDepenses', 'soldeGlobal'));
    return $pdf->download('rapport-global.pdf');
}

}
