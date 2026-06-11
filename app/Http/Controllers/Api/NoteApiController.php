<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Note;
use App\Models\Inscription;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Trimestre;
use Illuminate\Support\Facades\Log;

class NoteApiController extends Controller
{
    /**
     * Liste des notes
     */
    public function index()
    {
        $notes = Note::with([
            'eleve',
            'matiere',
            'classe',
            'trimestre'
        ])
        ->latest()
        ->paginate(200);

        return response()->json($notes);
    }

    /**
     * Créer une note
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inscription_id'    => 'required|exists:inscriptions,id',
            'matiere_id'        => 'required|exists:matieres,id',
            'trimestre_id'      => 'required|exists:trimestres,id',
            'annee_id'          => 'required|exists:annees,id',

            'moyenne_interro'   => 'nullable|numeric|min:0|max:20',
            'devoir1'           => 'nullable|numeric|min:0|max:20',
            'devoir2'           => 'nullable|numeric|min:0|max:20',
        ]);

        $inscription = Inscription::findOrFail($validated['inscription_id']);

        // Vérification doublon
        $exists = Note::where([
            'inscription_id' => $validated['inscription_id'],
            'matiere_id'     => $validated['matiere_id'],
            'trimestre_id'   => $validated['trimestre_id'],
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Cette note existe déjà.'
            ], 422);
        }

        $moyenne = $this->calculerMoyenneFlexible(
            $validated['moyenne_interro'] ?? null,
            $validated['devoir1'] ?? null,
            $validated['devoir2'] ?? null
        );

        if ($moyenne === null) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune note valide fournie.'
            ], 422);
        }

        $note = Note::create([
            'inscription_id'  => $validated['inscription_id'],
            'classe_id'       => $inscription->classe_id,
            'matiere_id'      => $validated['matiere_id'],
            'trimestre_id'    => $validated['trimestre_id'],
            'annee_id'        => $validated['annee_id'],

            'moyenne_interro' => $validated['moyenne_interro'],
            'devoir1'         => $validated['devoir1'],
            'devoir2'         => $validated['devoir2'],

            'moyenne_matiere' => $moyenne,
            'appreciation'    => $this->getAppreciation($moyenne),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note enregistrée avec succès.',
            'data'    => $note
        ], 201);
    }

    /**
     * Afficher une note
     */
    public function show($id)
    {
        $note = Note::with([
            'eleve',
            'matiere',
            'classe',
            'trimestre'
        ])->find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note introuvable.'
            ], 404);
        }

        return response()->json($note);
    }

    /**
     * Modifier une note
     */
    public function update(Request $request, $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note introuvable.'
            ], 404);
        }

        $validated = $request->validate([
            'moyenne_interro' => 'nullable|numeric|min:0|max:20',
            'devoir1'         => 'nullable|numeric|min:0|max:20',
            'devoir2'         => 'nullable|numeric|min:0|max:20',
        ]);

        $moyenne = $this->calculerMoyenneFlexible(
            $validated['moyenne_interro'] ?? null,
            $validated['devoir1'] ?? null,
            $validated['devoir2'] ?? null
        );

        if ($moyenne === null) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune note valide.'
            ], 422);
        }

        $note->update([
            'moyenne_interro' => $validated['moyenne_interro'],
            'devoir1'         => $validated['devoir1'],
            'devoir2'         => $validated['devoir2'],
            'moyenne_matiere' => $moyenne,
            'appreciation'    => $this->getAppreciation($moyenne),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note mise à jour avec succès.',
            'data'    => $note
        ]);
    }

    /**
     * Supprimer une note
     */
    public function destroy($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note introuvable.'
            ], 404);
        }

        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note supprimée avec succès.'
        ]);
    }

    /**
     * Notes par classe
     */
    public function getByClasse($classeId)
    {
        $notes = Note::with([
            'matiere',
            'trimestre',
            'eleve'
        ])
        ->where('classe_id', $classeId)
        ->get();

        return response()->json($notes);
    }

    /**
     * Notes par trimestre
     */
    public function getByTrimestre($trimestreId)
    {
        $notes = Note::with([
            'matiere',
            'classe',
            'eleve'
        ])
        ->where('trimestre_id', $trimestreId)
        ->get();

        return response()->json($notes);
    }

    /**
     * Calcul flexible moyenne
     */
    private function calculerMoyenneFlexible(
        $moyenne_interro,
        $devoir1,
        $devoir2
    ) {
        $notes = array_filter([
            $moyenne_interro,
            $devoir1,
            $devoir2
        ], function ($value) {
            return $value !== null;
        });

        if (empty($notes)) {
            return null;
        }

        return round(array_sum($notes) / count($notes), 2);
    }

    /**
     * Génération appréciation
     */
    private function getAppreciation(float $moyenne): string
    {
        if ($moyenne < 2) return 'Nul';
        elseif ($moyenne < 4) return 'Médiocre';
        elseif ($moyenne < 6) return 'Insuffisant';
        elseif ($moyenne < 8) return 'Passable';
        elseif ($moyenne < 10) return 'Moyen';
        elseif ($moyenne < 12) return 'Assez Bien';
        elseif ($moyenne < 14) return 'Bien';
        elseif ($moyenne < 16) return 'Très Bien';
        elseif ($moyenne < 18) return 'Excellent';
        elseif ($moyenne <= 20) return 'Exceptionnel';

        return 'Note invalide';
    }
}