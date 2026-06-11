<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    // Liste des matières avec leurs classes et enseignants
    public function index()
    {
        $matieres = Matiere::with('enseignant', 'classes')->paginate(150);
        return view('matieres.index', compact('matieres'));
    }

    // Formulaire de création
    public function create()
    {
         $classes = Classe::orderByNiveau()->get();
        $enseignants = Enseignant::all();
        return view('matieres.create', compact('classes', 'enseignants'));
    }

    // Stockage d'une nouvelle matière
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'coefficient' => 'required|integer|min:1',
             'classe_id' => 'required|exists:classes,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
        ]);

       
    // Récupère le niveau de la classe sélectionnée
    $classe = Classe::findOrFail($validated['classe_id']);
    $validated['niveau'] = $classe->niveau;

    // Vérifie doublon nom + niveau (dans la table matieres)
    $exists = Matiere::where('nom', $validated['nom'])
                     ->where('niveau', $validated['niveau'])
                     ->exists();

    if ($exists) {
        return back()->withErrors([
            'nom' => 'Cette matière existe déjà pour ce niveau.'
        ])->withInput();
    }

        $matiere = Matiere::create($validated);

        // Attacher automatiquement cette matière à toutes les classes du même niveau
        $classes = Classe::where('niveau', $validated['niveau'])->get();
        foreach ($classes as $classe) {
            $classe->matieres()->syncWithoutDetaching([$matiere->id]);
        }

        return redirect()->route('matieres.create')
                         ->with('success', 'Matière ajoutée et attachée aux classes du niveau avec succès.');
    }

    // Affichage d'une matière
    public function show(Matiere $matiere)
    {
        $matiere->load(['enseignant', 'classes']);
        return view('matieres.show', compact('matiere'));
    }

    // Formulaire d'édition
    public function edit(Matiere $matiere)
    {
         $classes = Classe::all();
        $enseignants = Enseignant::all();
        return view('matieres.edit', compact('matiere', 'classes', 'enseignants'));
    }

    // Mise à jour
    public function update(Request $request, Matiere $matiere)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'coefficient' => 'required|integer|min:1',
            'classe_id' => 'required|exists:classes,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
        ]);
         // Récupère le niveau de la classe sélectionnée
    $classe = Classe::findOrFail($validated['classe_id']);
    $validated['niveau'] = $classe->niveau;

        $matiere->update($validated);

        // Réattache automatiquement la matière aux classes du nouveau niveau
        $classes = Classe::where('niveau', $validated['niveau'])->get();
        $matiere->classes()->sync($classes->pluck('id'));

        return redirect()->route('matieres.index')
                         ->with('success', 'Matière mise à jour et réattache aux classes du niveau.');
    }

    // Suppression
    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return redirect()->route('matieres.index')
                         ->with('success', 'Matière supprimée avec succès.');
    }

    // API : récupérer les matières d'une classe
    /**public function getParClasse($classeId)
    {
        $classe = \App\Models\Classe::with('matieres')->find($classeId);
        return response()->json(['matieres' => $classe?->matieres ?? []]);
    }

    // API simplifiée pour AJAX
    public function getByClasse($classeId)
    {
        $classe = \App\Models\Classe::with('matieres')->find($classeId);
        return response()->json($classe?->matieres ?? []);
    }
 
    // MatiereController.php
public function getMatieresByClasse($classeId)
{
    $matieres = \App\Models\Matiere::join('classe_matiere', 'matieres.id', '=', 'classe_matiere.matiere_id')
                ->where('classe_matiere.classe_id', $classeId)
                ->select('matieres.id', 'matieres.nom')
                ->get();

    return response()->json($matieres);
}**/


}
