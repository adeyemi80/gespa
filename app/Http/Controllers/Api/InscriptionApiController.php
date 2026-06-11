<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Note;
use App\Models\Moyenne;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InscriptionApiController extends Controller
{
    /**
     * Liste des inscriptions
     */
    public function index()
    {
        $inscriptions = Inscription::with([
            'eleve',
            'classe',
            'annee'
        ])->paginate(100);

        return response()->json($inscriptions);
    }

    /**
     * Créer une inscription
     */
    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'  => 'required|exists:eleves,id',
            'classe_id' => 'required|exists:classes,id',
            'annee_id'  => 'required|exists:annees,id',
            'note_id'   => 'required|exists:notes,id',
        ]);

        // Vérifier double inscription
        $existe = Inscription::where('eleve_id', $request->eleve_id)
            ->where('annee_id', $request->annee_id)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Cet élève est déjà inscrit pour cette année scolaire.'
            ], 422);
        }

        // Vérifier moyenne
        $moyenne = Moyenne::where('eleve_id', $request->eleve_id)
            ->where('classe_id', $request->classe_id)
            ->where('annee_id', $request->annee_id)
            ->value('moyenne_generale');

        if (is_null($moyenne)) {
            return response()->json([
                'success' => false,
                'message' => 'La moyenne annuelle n’est pas encore calculée.'
            ], 422);
        }

        $decision = $moyenne >= 10 ? 'passé' : 'redoublé';

        $inscription = DB::transaction(function () use ($request, $moyenne, $decision) {

            $inscription = Inscription::create([
                'eleve_id'         => $request->eleve_id,
                'classe_id'        => $request->classe_id,
                'annee_id'         => $request->annee_id,
                'note_id'          => $request->note_id,
                'moyenne_annuelle' => $moyenne,
                'decision'         => $decision,
                'date_inscription' => now(),
            ]);

            // Génération automatique des frais
            $classe = Classe::with('frais')->findOrFail($request->classe_id);

            foreach ($classe->frais as $frais) {

                DB::table('inscription_frais')->insert([
                    'inscription_id' => $inscription->id,
                    'frais_id'       => $frais->id,
                    'annee_id'       => $request->annee_id,
                    'montant_frais'  => $frais->montant,
                    'montant_paye'   => 0,
                    'reste'          => $frais->montant,
                    'statut'         => 'non_payé',
                    'est_arriere'    => false,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            return $inscription;
        });

        return response()->json([
            'success' => true,
            'message' => 'Inscription créée avec succès.',
            'data'    => $inscription->load([
                'eleve',
                'classe',
                'annee'
            ])
        ], 201);
    }

    /**
     * Afficher une inscription
     */
    public function show($id)
    {
        $inscription = Inscription::with([
            'eleve',
            'classe',
            'annee'
        ])->find($id);

        if (!$inscription) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription introuvable.'
            ], 404);
        }

        return response()->json($inscription);
    }

    /**
     * Modifier une inscription
     */
    public function update(Request $request, $id)
    {
        $inscription = Inscription::find($id);

        if (!$inscription) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription introuvable.'
            ], 404);
        }

        $request->validate([
            'eleve_id'         => 'required|exists:eleves,id',
            'classe_id'        => 'required|exists:classes,id',
            'annee_id'         => 'required|exists:annees,id',
            'note_id'          => 'required|exists:notes,id',
            'date_inscription' => 'required|date',
        ]);

        $inscription->update([
            'eleve_id'         => $request->eleve_id,
            'classe_id'        => $request->classe_id,
            'annee_id'         => $request->annee_id,
            'note_id'          => $request->note_id,
            'date_inscription' => $request->date_inscription,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inscription mise à jour avec succès.',
            'data'    => $inscription->load([
                'eleve',
                'classe',
                'annee'
            ])
        ]);
    }

    /**
     * Supprimer une inscription
     */
    public function destroy($id)
    {
        $inscription = Inscription::find($id);

        if (!$inscription) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription introuvable.'
            ], 404);
        }

        $inscription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inscription supprimée avec succès.'
        ]);
    }
}