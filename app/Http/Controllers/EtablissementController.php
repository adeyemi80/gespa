<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{
    /**
     * Afficher la liste des établissements.
     */
    public function index()
    {
        $etablissements = Etablissement::latest()->get();

        return view('etablissements.index', compact('etablissements'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('etablissements.create');
    }

    /**
     * Enregistrer un nouvel établissement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'ifu' => ['nullable', 'string', 'max:100'],
            'npi' => ['nullable', 'string', 'max:100'],
            'representant' => ['required', 'string', 'max:255'],
            'fonction' => ['required', 'string', 'max:255'],
        ]);

        Etablissement::create($validated);

        return redirect()
            ->route('etablissements.index')
            ->with('success', 'Établissement enregistré avec succès.');
    }

    /**
     * Afficher les informations d'un établissement.
     */
    public function show(Etablissement $etablissement)
    {
        return view('etablissements.show', compact('etablissement'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Etablissement $etablissement)
    {
        return view('etablissements.edit', compact('etablissement'));
    }

    /**
     * Mettre à jour un établissement.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'ifu' => ['nullable', 'string', 'max:100'],
            'npi' => ['nullable', 'string', 'max:100'],
            'representant' => ['required', 'string', 'max:255'],
            'fonction' => ['required', 'string', 'max:255'],
        ]);

        $etablissement->update($validated);

        return redirect()
            ->route('etablissements.index')
            ->with('success', 'Établissement modifié avec succès.');
    }

    /**
     * Supprimer un établissement.
     */
    public function destroy(Etablissement $etablissement)
    {
        $etablissement->delete();

        return redirect()
            ->route('etablissements.index')
            ->with('success', 'Établissement supprimé avec succès.');
    }
}