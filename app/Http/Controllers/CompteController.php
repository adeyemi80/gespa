<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function index()
    {
        $comptes = Compte::all();
        return view('comptes.index', compact('comptes'));
    }

    public function create()
    {
        return view('comptes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'solde_initial' => 'required|numeric|min:0',
        ]);

        $compte = Compte::create([
            'nom' => $request->nom,
            'solde_initial' => $request->solde_initial,
            'solde_actuel' => $request->solde_initial,
        ]);

        return redirect()->route('comptes.index')
                         ->with('success', 'Compte créé avec succès.');
    }

    public function edit(Compte $compte)
    {
        return view('comptes.edit', compact('compte'));
    }

    public function update(Request $request, Compte $compte)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $compte->update($request->all());

        return redirect()->route('comptes.index')
                         ->with('success', 'Compte mis à jour.');
    }

    public function destroy(Compte $compte)
    {
        $compte->delete();
        return redirect()->route('comptes.index')
                         ->with('success', 'Compte supprimé.');
    }
}
