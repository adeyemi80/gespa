<?php

namespace App\Http\Controllers;

use App\Models\ContratPrestataire;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratPrestataireController extends Controller
{
    /**
     * Liste des contrats.
     */
    public function index()
    {
        $contrats = ContratPrestataire::latest()->paginate(15);
        return view('contrats.prestataires.index', compact('contrats')); // ✅ Corrigé
    }

    /**
     * Formulaire de création.
     */

public function create()
{
    $etablissement = Etablissement::first();

    return view('contrats.prestataires.create', compact('etablissement'));
}


    /**
     * Enregistrement.
     */
    public function store(Request $request)
    {
        $request->validate([

            'etablissement'            => 'required|string|max:255',
            'adresse_etablissement'    => 'required|string',
            'representant'             => 'required|string|max:255',
            'fonction'                 => 'required|string|max:255',

            'prestataire_nom'          => 'required|string|max:255',
            'prestataire_adresse'      => 'required|string',
            'telephone'                => 'required|string|max:50',
            'ifu'                      => 'nullable|string|max:100',

            'objet_contrat'            => 'required|string',

            'montant_total'            => 'required|numeric|min:0',
            'montant_total_lettre'     => 'required|string',

            'acompte'                  => 'required|numeric|min:0',
            'acompte_lettre'           => 'required|string',

            'date_limite_livraison'    => 'required|date',

            'lieu_signature'           => 'required|string|max:255',
            'date_signature'           => 'required|date',

            'mention_manuelle'         => 'nullable|string',

        ]);

        $data = $request->all();

        // Calcul automatique du reliquat
        $data['reliquat'] = $data['montant_total'] - $data['acompte'];

        // A compléter avec ton convertisseur
        $data['reliquat_lettre'] = '';

        $data['etat'] = 'brouillon';

        $contrat = ContratPrestataire::create($data);

        return redirect()
            ->route('contrats-prestataires.show', $contrat)
            ->with('success', 'Contrat enregistré avec succès.');
    }

    /**
     * Affichage.
     */
    public function show(ContratPrestataire $contrat)
    {
        return view('contrats.prestataires.show', compact('contrat'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(ContratPrestataire $contrat)
    {
        return view('contrats.prestataires.edit', compact('contrat'));
    }

    /**
     * Mise à jour.
     */
    public function update(Request $request, ContratPrestataire $contrat)
    {
        $request->validate([

            'etablissement'            => 'required|string|max:255',
            'adresse_etablissement'    => 'required|string',
            'representant'             => 'required|string|max:255',
            'fonction'                 => 'required|string|max:255',

            'prestataire_nom'          => 'required|string|max:255',
            'prestataire_adresse'      => 'required|string',
            'telephone'                => 'required|string|max:50',
            'ifu'                      => 'nullable|string|max:100',

            'objet_contrat'            => 'required|string',

            'montant_total'            => 'required|numeric|min:0',
            'montant_total_lettre'     => 'required|string',

            'acompte'                  => 'required|numeric|min:0',
            'acompte_lettre'           => 'required|string',

            'date_limite_livraison'    => 'required|date',

            'lieu_signature'           => 'required|string|max:255',
            'date_signature'           => 'required|date',

            'mention_manuelle'         => 'nullable|string',
        ]);

        $data = $request->all();

        $data['reliquat'] = $data['montant_total'] - $data['acompte'];

        // A remplacer par ton convertisseur
        $data['reliquat_lettre'] = '';

        $contrat->update($data);

        return redirect()
            ->route('contrats-prestataires.show', $contrat)
            ->with('success', 'Contrat modifié avec succès.');
    }

    /**
     * Suppression.
     */
    public function destroy(ContratPrestataire $contrat)
    {
        $contrat->delete();

        return redirect()
            ->route('contrats-prestataires.index')
            ->with('success', 'Contrat supprimé avec succès.');
    }

    /**
     * Aperçu avant impression.
     */
    public function preview(ContratPrestataire $contrat)
    {
        return view('contrats.prestataires.preview', compact('contrat'));
    }

    /**
     * Export PDF.
     */
   public function pdf($id)
{
    $contrat = ContratPrestataire::findOrFail($id);

    $pdf = Pdf::loadView(
        'contrats.prestataires.pdf',
        compact('contrat')
    );

    $pdf->setPaper('a4', 'portrait');

    return $pdf->download(
        'contrat-' . $contrat->id . '.pdf'
    );
}

    /**
     * Validation du contrat.
     */
    public function valider(ContratPrestataire $contrat)
    {
        $contrat->update([
            'etat' => 'validé'
        ]);

        return back()->with('success', 'Contrat validé.');
    }

    /**
     * Marquer comme terminé.
     */
    public function terminer(ContratPrestataire $contrat)
    {
        $contrat->update([
            'etat' => 'terminé'
        ]);

        return back()->with('success', 'Contrat terminé.');
    }

    /**
     * Annulation.
     */
    public function annuler(ContratPrestataire $contrat)
    {
        $contrat->update([
            'etat' => 'annulé'
        ]);

        return back()->with('success', 'Contrat annulé.');
    }
}