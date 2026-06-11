<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    /**
     * Affiche la liste des enseignants.
     */
    public function index()
{
    $classes = Classe::all();
    $matieres = Matiere::all();

    $enseignants = Enseignant::with(['classes', 'matiere'])
        ->latest()
        ->paginate(10);

    return view('enseignants.index', compact('enseignants', 'classes', 'matieres'));
}

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('enseignants.create');
    }

    /**
     * Enregistre un nouvel enseignant.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'date_naissance' => 'nullable|date',
            'sexe' => 'required|in:M,F',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'required|email|unique:enseignants,email',
            'matricule' => 'required|unique:enseignants,matricule',
        ]);

        Enseignant::create($request->all());

        return redirect()->route('enseignants.index')->with('success', 'Enseignant ajouté avec succès.');
    }

    /**
     * Affiche un enseignant donné.
     */
    public function show(Enseignant $enseignant)
    {
        return view('enseignants.show', compact('enseignant'));
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Enseignant $enseignant)
    {
        return view('enseignants.edit', compact('enseignant'));
    }

    /**
     * Met à jour les données d’un enseignant.
     */
    public function update(Request $request, Enseignant $enseignant)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'date_naissance' => 'nullable|date',
            'sexe' => 'required|in:M,F',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'required|email|unique:enseignants,email,' . $enseignant->id,
            'matricule' => 'required|unique:enseignants,matricule,' . $enseignant->id,
        ]);

        $enseignant->update($request->all());

        return redirect()->route('enseignants.index')->with('success', 'Enseignant modifié avec succès.');
    }

    /**
     * Supprime un enseignant.
     */
    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();
        return redirect()->route('enseignants.index')->with('success', 'Enseignant supprimé avec succès.');
    }
}
