<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trimestre;
use App\Models\Annee;
use Illuminate\Http\Request;

class TrimestreApiController extends Controller
{
    /* =======================
     * LISTE
     * ======================= */
    public function index()
    {
        $trimestres = Trimestre::with('annees')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $trimestres
        ]);
    }

    /* =======================
     * ENREGISTREMENT
     * ======================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'       => 'required|string|max:255',
            'ordre'     => 'required|integer|min:1|max:3',
            'periode'   => [
                'nullable',
                'regex:/^[a-zA-Zàâäéèêëîïôöùûüÿç]+-[a-zA-Zàâäéèêëîïôöùûüÿç]+$/i'
            ],
            'annee_id'  => 'required|exists:annees,id',
        ]);

        $annee = Annee::findOrFail($validated['annee_id']);

        $exists = $annee->trimestres()
            ->wherePivot('active', true)
            ->where('ordre', $validated['ordre'])
            ->exists();

        if ($exists) {

            return response()->json([
                'success' => false,
                'message' => 'Un trimestre avec cet ordre existe déjà pour cette année.'
            ], 422);
        }

        $trimestre = Trimestre::create([
            'nom'     => $validated['nom'],
            'ordre'   => $validated['ordre'],
            'periode' => $validated['periode'] ?? null,
        ]);

        $trimestre->annees()->attach([
            $annee->id => [
                'active' => true
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trimestre créé avec succès.',
            'data' => $trimestre
        ], 201);
    }

    /* =======================
     * AFFICHAGE
     * ======================= */
    public function show(Trimestre $trimestre)
    {
        $trimestre->load('annees');

        return response()->json([
            'success' => true,
            'data' => $trimestre
        ]);
    }

    /* =======================
     * MISE À JOUR
     * ======================= */
    public function update(Request $request, Trimestre $trimestre)
    {
        $validated = $request->validate([
            'nom'       => 'required|string|max:255',
            'ordre'     => 'required|integer|min:1|max:3',
            'periode'   => [
                'nullable',
                'regex:/^[a-zA-Zàâäéèêëîïôöùûüÿç]+-[a-zA-Zàâäéèêëîïôöùûüÿç]+$/i'
            ],
            'annee_id'  => 'required|exists:annees,id',
        ]);

        $annee = Annee::findOrFail($validated['annee_id']);

        $exists = $annee->trimestres()
            ->wherePivot('active', true)
            ->where('ordre', $validated['ordre'])
            ->where('trimestres.id', '!=', $trimestre->id)
            ->exists();

        if ($exists) {

            return response()->json([
                'success' => false,
                'message' => 'Un trimestre avec cet ordre existe déjà pour cette année.'
            ], 422);
        }

        $trimestre->update([
            'nom'     => $validated['nom'],
            'ordre'   => $validated['ordre'],
            'periode' => $validated['periode'] ?? null,
        ]);

        $trimestre->annees()->syncWithoutDetaching([
            $annee->id => [
                'active' => true
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trimestre mis à jour avec succès.',
            'data' => $trimestre
        ]);
    }

    /* =======================
     * SUPPRESSION
     * ======================= */
    public function destroy(Trimestre $trimestre)
    {
        $trimestre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Trimestre supprimé avec succès.'
        ]);
    }

    /* =======================
     * TOGGLE ACTIVE PIVOT
     * ======================= */
    public function toggleActive(Trimestre $trimestre, Annee $annee)
    {
        $pivot = $trimestre->annees()
            ->where('annee_id', $annee->id)
            ->first();

        if (!$pivot) {

            return response()->json([
                'success' => false,
                'message' => 'Relation inexistante.'
            ], 404);
        }

        $trimestre->annees()->updateExistingPivot(
            $annee->id,
            [
                'active' => !$pivot->pivot->active
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Statut du trimestre modifié.'
        ]);
    }

    /* =======================
     * TRIMESTRES PAR ANNÉE
     * ======================= */
    public function getTrimestresByAnnee($anneeId)
    {
        $annee = Annee::findOrFail($anneeId);

        $trimestres = $annee->trimestres()
            ->wherePivot('active', true)
            ->orderBy('ordre')
            ->get([
                'trimestres.id',
                'trimestres.nom',
                'trimestres.ordre',
                'trimestres.periode'
            ]);

        return response()->json([
            'success' => true,
            'data' => $trimestres
        ]);
    }
}