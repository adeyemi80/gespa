<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Annee;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    /**
     * Affiche la liste des classes avec pagination
     */
    public function index()
    {
        $cycles = Cycle::all();
        $classes = Classe::with('annees')->paginate(90);
        return view('classes.index', compact('classes', 'cycles'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        // On peut sélectionner une année pour l'attachement
        $annees = Annee::all();
          $cycles = Cycle::all();
        return view('classes.create', compact('annees', 'cycles'));
    }

    /**
     * Enregistre une nouvelle classe et l'attache à une année
     */
    public function store(Request $request)
{
    
    $validated = $request->validate([
        'nom' => [
            'required',
            'string',
            'max:100',
            \Illuminate\Validation\Rule::unique('classes')->where(function ($query) use ($request) {
                return $query->where('niveau', $request->niveau);
            }),
        ],
        'niveau'   => 'required|string|max:50',
        'cycle_id' => 'required|exists:cycles,id', // ✅ ICI
        'annee_id' => 'required|exists:annees,id',
        'active'   => 'required|boolean',
    ], [
        'nom.unique' => 'Cette classe avec ce niveau existe déjà.',
    ]);
//dd($request->all());
    // ✅ Création avec cycle_id
    $classe = Classe::create([
        'nom'      => $validated['nom'],
        'niveau'   => $validated['niveau'],
        'cycle_id' => $validated['cycle_id'], // ✅ IMPORTANT
        'active'   => true,
    ]);

    // ✅ Attacher année
    $classe->annees()->attach($validated['annee_id'], ['active' => true]);

    // 🔥 Attacher matières
    $this->attacherMatieresParNiveau($classe);

    return redirect()->route('classes.create')
        ->with('success', 'Classe créée avec cycle et matières.');
}

    /**
     * Formulaire d'édition
     */
    public function edit(Classe $classe)
    {
        $annees = Annee::all();
         $cycles = Cycle::all();
        return view('classes.edit', compact('classe', 'annees', 'cycles'));
    }

    /**
     * Met à jour une classe
     */

    public function show(Classe $classe)
{
    return view('classes.show', compact('classe'));
}

public function update(Request $request, Classe $classe)
{
    // 🔁 Sauvegarder l’ancien niveau
    $oldNiveau = $classe->niveau;

    // ✅ Validation
    $validated = $request->validate([
    'nom' => [
        'required',
        'string',
        'max:100',
        Rule::unique('classes')
            ->ignore($classe->id)
            ->where(fn ($query) => $query->where('niveau', $request->niveau)),
    ],
    'niveau'   => 'required|string|max:50',
    'cycle_id' => 'required|exists:cycles,id', // ✅
    'annee_id' => 'required|exists:annees,id',
    'active'   => 'nullable|boolean',
]);

$classe->update([
    'nom'      => $validated['nom'],
    'niveau'   => $validated['niveau'],
    'cycle_id' => $validated['cycle_id'], // ✅
    'active'   => $request->boolean('active'),
]);

    // ✅ Mise à jour du pivot classe_annee (activation par année)
    $classe->annees()->syncWithoutDetaching([
        $validated['annee_id'] => [
            'active' => true
        ]
    ]);

    // 🔁 Si le niveau a changé → rattacher les matières
    if ($oldNiveau !== $validated['niveau']) {
        $this->attacherMatieresParNiveau($classe);
    }

    return redirect()->route('classes.index')
        ->with('success', 'Classe mise à jour avec succès.');
}


    /**
     * Supprime une classe
     */
    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')
                         ->with('success', 'Classe supprimée avec succès.');
    }
 // Active ou désactive une classe
    public function toggle(Classe $classe)
    {
        $classe->active = !$classe->active;
        $classe->save();

        return redirect()->back()
            ->with('success', 'La classe a été ' . ($classe->active ? 'activée' : 'désactivée') . ' avec succès.');
    }
    public function toggleActiveAnnees(Classe $classe, Annee $annee)
{
    // Récupérer le pivot classe_annee
    $classe_annee = $classe->annees()->where('annee_id', $annee->id)->first();

    if (!$classe_annee) {
        return redirect()->back()->with('error', "Cette classe n'est pas attachée à l'année sélectionnée.");
    }

    // Inverser la valeur de active dans le pivot
    $classe->annees()->updateExistingPivot($annee->id, [
        'active' => !$classe_annee->pivot->active
    ]);

    return redirect()->back()->with('success', "La classe '{$classe->nom}' pour l'année '{$annee->nom}' est maintenant " . (!$classe_annee->pivot->active ? 'active' : 'inactive') . ".");
}

private function attacherMatieresParNiveau(Classe $classe)
{
    $matieres = Matiere::where('niveau', $classe->niveau)->get();

    foreach ($matieres as $matiere) {
        $classe->matieres()->syncWithoutDetaching([
            $matiere->id
        ]);
    }
}

public function classesByAnnee($anneeId)
{
    $annee = Annee::findOrFail($anneeId);

    $classes = $annee->classes()
        ->select('classes.id', 'classes.nom')
        ->orderBy('classes.nom')
        ->get();

    return response()->json($classes);
}

public function getMatieres($id)
{
    $matieres = \App\Models\Matiere::select('matieres.id', 'matieres.nom')  // ← Qualifiez !
        ->join('classe_matiere', 'classe_matiere.matiere_id', '=', 'matieres.id')
        ->where('classe_matiere.classe_id', $id)
        ->get();
    
    return response()->json($matieres);
}
public function getAnneeClassesByCycle($anneeId, $cycleId)
{
    $annee = Annee::findOrFail($anneeId);

    $classes = $annee->classes()
        ->where('cycle_id', $cycleId)
        ->get();

    return response()->json($classes);
}
public function getClassesByCycle($cycleId)
{
    $classes = Classe::where('cycle_id', $cycleId)
        ->orderBy('ordre')
        ->get();

    return response()->json($classes);
}

}
