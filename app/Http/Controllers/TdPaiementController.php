<?php

namespace App\Http\Controllers;

use App\Models\TdPaiement;
use App\Models\Eleve;
use App\Models\Annee;
use Illuminate\Http\Request;

class TdPaiementController extends Controller
{
    /**
     * Liste des paiements, avec filtres optionnels par élève et/ou année.
     */
    public function index(Request $request)
    {
        $annees = Annee::orderByDesc('id')->get();
        $annee_id = $request->get('annee_id', Annee::where('en_cours', true)->first()?->id);

        $query = TdPaiement::with(['eleve', 'annee'])
            ->when($annee_id, fn($q) => $q->where('annee_id', $annee_id))
            ->when($request->filled('eleve_id'), fn($q) => $q->where('eleve_id', $request->eleve_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('eleve', function ($q2) use ($request) {
                    $q2->where('nom', 'like', '%' . $request->search . '%')
                       ->orWhere('prenom', 'like', '%' . $request->search . '%');
                });
            })
            ->orderByDesc('date_paiement')
            ->paginate(20)
            ->withQueryString();

        $total = TdPaiement::when($annee_id, fn($q) => $q->where('annee_id', $annee_id))
            ->when($request->filled('eleve_id'), fn($q) => $q->where('eleve_id', $request->eleve_id))
            ->sum('montant');

        return view('td_paiements.index', compact('query', 'annees', 'annee_id', 'total'));
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $annees = Annee::orderByDesc('id')->get();
        $annee_id = $request->get('annee_id', Annee::where('en_cours', true)->first()?->id);

        // Pré-sélection d'un élève si passé en query string (depuis fiche élève)
        $eleve = $request->filled('eleve_id')
            ? Eleve::findOrFail($request->eleve_id)
            : null;

        return view('td_paiements.create', compact('annees', 'annee_id', 'eleve'));
    }

    /**
     * Enregistrement d'un nouveau paiement.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'eleve_id'      => 'required|exists:eleves,id',
            'annee_id'      => 'required|exists:annees,id',
            'montant'       => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'reference'     => 'nullable|string|max:100',
            'observation'   => 'nullable|string|max:500',
        ]);

        TdPaiement::create($data);

        return redirect()
            ->route('td-paiements.create', ['annee_id' => $data['annee_id']])
            ->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Détail d'un paiement (lecture seule).
     */
    public function show(TdPaiement $tdPaiement)
    {
        $tdPaiement->load(['eleve', 'annee']);

        return view('td_paiements.show', compact('tdPaiement'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(TdPaiement $tdPaiement)
    {
        $annees = Annee::orderByDesc('id')->get();

        return view('td_paiements.edit', compact('tdPaiement', 'annees'));
    }

    /**
     * Mise à jour d'un paiement.
     */
    public function update(Request $request, TdPaiement $tdPaiement)
    {
        $data = $request->validate([
            'eleve_id'      => 'required|exists:eleves,id',
            'annee_id'      => 'required|exists:annees,id',
            'montant'       => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'reference'     => 'nullable|string|max:100',
            'observation'   => 'nullable|string|max:500',
        ]);

        $tdPaiement->update($data);

        return redirect()
            ->route('td-paiements.index', ['annee_id' => $data['annee_id']])
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Suppression d'un paiement.
     */
    public function destroy(TdPaiement $tdPaiement)
    {
        $annee_id = $tdPaiement->annee_id;
        $tdPaiement->delete();

        return redirect()
            ->route('td-paiements.index', ['annee_id' => $annee_id])
            ->with('success', 'Paiement supprimé.');
    }
}