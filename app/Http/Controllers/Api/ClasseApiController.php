<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Annee;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseApiController extends Controller
{
    /**
     * Liste des classes
     */
    public function index()
    {
        $classes = Classe::with(['annees', 'cycle'])
            ->paginate(90);

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Enregistrer une nouvelle classe
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:100',
                Rule::unique('classes')->where(function ($query) use ($request) {
                    return $query->where('niveau', $request->niveau);
                }),
            ],
            'niveau'   => 'required|string|max:50',
            'cycle_id' => 'required|exists:cycles,id',
            'annee_id' => 'required|exists:annees,id',
            'active'   => 'required|boolean',
        ], [
            'nom.unique' => 'Cette classe avec ce niveau existe déjà.',
        ]);

        $classe = Classe::create([
            'nom'      => $validated['nom'],
            'niveau'   => $validated['niveau'],
            'cycle_id' => $validated['cycle_id'],
            'active'   => true,
        ]);

        // Attacher année
        $classe->annees()->attach($validated['annee_id'], [
            'active' => true
        ]);

        // Attacher matières
        $this->attacherMatieresParNiveau($classe);

        return response()->json([
            'success' => true,
            'message' => 'Classe créée avec succès',
            'data' => $classe->load(['annees', 'cycle'])
        ], 201);
    }

    /**
     * Afficher une classe
     */
    public function show(Classe $classe)
    {
        $classe->load(['annees', 'cycle', 'matieres']);

        return response()->json([
            'success' => true,
            'data' => $classe
        ]);
    }

    /**
     * Mettre à jour une classe
     */
    public function update(Request $request, Classe $classe)
    {
        $oldNiveau = $classe->niveau;

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
            'cycle_id' => 'required|exists:cycles,id',
            'annee_id' => 'required|exists:annees,id',
            'active'   => 'nullable|boolean',
        ]);

        $classe->update([
            'nom'      => $validated['nom'],
            'niveau'   => $validated['niveau'],
            'cycle_id' => $validated['cycle_id'],
            'active'   => $request->boolean('active'),
        ]);

        // Pivot année
        $classe->annees()->syncWithoutDetaching([
            $validated['annee_id'] => [
                'active' => true
            ]
        ]);

        // Réattacher matières si niveau changé
        if ($oldNiveau !== $validated['niveau']) {
            $this->attacherMatieresParNiveau($classe);
        }

        return response()->json([
            'success' => true,
            'message' => 'Classe mise à jour avec succès',
            'data' => $classe->load(['annees', 'cycle'])
        ]);
    }

    /**
     * Supprimer une classe
     */
    public function destroy(Classe $classe)
    {
        $classe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Classe supprimée avec succès'
        ]);
    }

    /**
     * Activer / désactiver une classe
     */
    public function toggle(Classe $classe)
    {
        $classe->active = !$classe->active;
        $classe->save();

        return response()->json([
            'success' => true,
            'message' => 'Classe ' . ($classe->active ? 'activée' : 'désactivée'),
            'data' => $classe
        ]);
    }

    /**
     * Activer / désactiver une année pour une classe
     */
    public function toggleActiveAnnees(Classe $classe, Annee $annee)
    {
        $classe_annee = $classe->annees()
            ->where('annee_id', $annee->id)
            ->first();

        if (!$classe_annee) {
            return response()->json([
                'success' => false,
                'message' => "Cette classe n'est pas attachée à cette année."
            ], 404);
        }

        $nouvelEtat = !$classe_annee->pivot->active;

        $classe->annees()->updateExistingPivot($annee->id, [
            'active' => $nouvelEtat
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'active' => $nouvelEtat
        ]);
    }

    /**
     * Classes par année
     */
    public function classesByAnnee($anneeId)
    {
        $annee = Annee::findOrFail($anneeId);

        $classes = $annee->classes()
            ->select('classes.id', 'classes.nom')
            ->orderBy('classes.nom')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Matières d'une classe
     */
    public function getMatieres($id)
    {
        $matieres = Matiere::select('matieres.id', 'matieres.nom')
            ->join('classe_matiere', 'classe_matiere.matiere_id', '=', 'matieres.id')
            ->where('classe_matiere.classe_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $matieres
        ]);
    }

    /**
     * Classes d'une année par cycle
     */
    public function getAnneeClassesByCycle($anneeId, $cycleId)
    {
        $annee = Annee::findOrFail($anneeId);

        $classes = $annee->classes()
            ->where('cycle_id', $cycleId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Classes par cycle
     */
    public function getClassesByCycle($cycleId)
    {
        $classes = Classe::where('cycle_id', $cycleId)
            ->orderBy('ordre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Attacher automatiquement les matières selon le niveau
     */
    private function attacherMatieresParNiveau(Classe $classe)
    {
        $matieres = Matiere::where('niveau', $classe->niveau)->get();

        foreach ($matieres as $matiere) {
            $classe->matieres()->syncWithoutDetaching([
                $matiere->id
            ]);
        }
    }
}