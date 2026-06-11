<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Categorie;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['categorie', 'compte', 'auteur'])->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = Categorie::all();
        $comptes = Compte::all();
        return view('transactions.create', compact('categories', 'comptes'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'date_transaction' => 'required|date',
            'type' => 'required|in:recette,dépense',
            'categorie_id' => 'required|exists:categories,id',
            'compte_id' => 'required|exists:comptes,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Transaction::create([
            'date_transaction' => $request->date_transaction,
            'type' => $request->type,
            'categorie_id' => $request->categorie_id,
            'compte_id' => $request->compte_id,
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // Mettre à jour le solde du compte
        $compte = Compte::find($request->compte_id);
        $compte->majSolde();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaction enregistrée.');
    }

    public function edit(Transaction $transaction)
    {
        $categories = Categorie::all();
        $comptes = Compte::all();
        return view('transactions.edit', compact('transaction', 'categories', 'comptes'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'date_transaction' => 'required|date',
            'type' => 'required|in:recette,dépense',
            'categorie_id' => 'required|exists:categories,id',
            'compte_id' => 'required|exists:comptes,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $transaction->update($request->all());

        // Recalcul du solde du compte concerné
        $transaction->compte->majSolde();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaction mise à jour.');
    }

    public function destroy(Transaction $transaction)
    {
        $compte = $transaction->compte;
        $transaction->delete();
        $compte->majSolde();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaction supprimée.');
    }
}
