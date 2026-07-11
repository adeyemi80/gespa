<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Oloye;
use Illuminate\Http\Request;

class OloyeController extends Controller
{
    /**
     * Afficher la liste des dépenses.
     */
    public function index()
    {
        $oloyes = Oloye::latest('date')->paginate(20);
        $total = Oloye::sum('montant');

        return view('oloyes.index', compact('oloyes', 'total'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('oloyes.create');
    }

    /**
     * Enregistrer une nouvelle dépense.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'libelle' => 'required|string|max:255',
            'categorie' => 'nullable|string|max:255',
            'montant' => 'required|numeric|min:0',
            'beneficiaire' => 'nullable|string|max:255',
            'observation' => 'nullable|string',
        ]);

        Oloye::create($request->all());

        return redirect()
            ->route('oloyes.index')
            ->with('success', 'La dépense a été enregistrée avec succès.');
    }

    /**
     * Afficher les détails d'une dépense.
     */
    public function show(Oloye $oloye)
    {
        return view('oloyes.show', compact('oloye'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Oloye $oloye)
    {
        return view('oloyes.edit', compact('oloye'));
    }

    /**
     * Mettre à jour une dépense.
     */
    public function update(Request $request, Oloye $oloye)
    {
        $request->validate([
            'date' => 'required|date',
            'libelle' => 'required|string|max:255',
            'categorie' => 'nullable|string|max:255',
            'montant' => 'required|numeric|min:0',
            'beneficiaire' => 'nullable|string|max:255',
            'observation' => 'nullable|string',
        ]);

        $oloye->update($request->all());

        return redirect()
            ->route('oloyes.index')
            ->with('success', 'La dépense a été modifiée avec succès.');
    }

    /**
     * Supprimer une dépense.
     */
    public function destroy(Oloye $oloye)
    {
        $oloye->delete();

        return redirect()
            ->route('oloyes.index')
            ->with('success', 'La dépense a été supprimée avec succès.');
    }
    public function pdf()
{
    $oloyes = Oloye::orderBy('date', 'asc')->get();

    $total = Oloye::sum('montant');

    $pdf = Pdf::loadView('oloyes.pdf', [
        'oloyes' => $oloyes,
        'total' => $total,
    ]);

    $pdf->setPaper('A4', 'portrait');

    return $pdf->download('depenses_oloye.pdf');
}

}