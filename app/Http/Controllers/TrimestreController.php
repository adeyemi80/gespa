<?php

namespace App\Http\Controllers;

use App\Models\Trimestre;
use App\Models\Annee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrimestreController extends Controller
{
    /* =======================
     * LISTE
     * ======================= */
    public function index()
    {
        $trimestres = Trimestre::with('annees')->paginate(10);
        return view('trimestres.index', compact('trimestres'));
    }

    /* =======================
     * AFFICHAGE
     * ======================= */
    public function show(Trimestre $trimestre)
    {
        $trimestre->load('annees');
        return view('trimestres.show', compact('trimestre'));
    }

    
    /* =======================
     * ÉDITION
     * ======================= */
    public function edit(Trimestre $trimestre)
    {
        $annees = Annee::all();
        $trimestre->load('annees');
        return view('trimestres.edit', compact('trimestre', 'annees'));
    }

    /* =======================
     * MISE À JOUR
     * ======================= */
    public function update(Request $request, Trimestre $trimestre)
    {
        $validated = $request->validate([
            'nom'      => 'required|string|max:255',
            'ordre'    => 'required|integer|min:1|max:3',
            'periode'  => ['nullable','regex:/^[a-zA-Zàâäéèêëîïôöùûüÿç]+-[a-zA-Zàâäéèêëîïôöùûüÿç]+$/i'],
            'annee_id' => 'required|exists:annees,id',
        ]);

        $annee = Annee::findOrFail($validated['annee_id']);

        $exists = $annee->trimestres()
                        ->wherePivot('active', true)
                        ->where('ordre', $validated['ordre'])
                        ->where('trimestres.id', '!=', $trimestre->id)
                        ->exists();

        if ($exists) {
            return back()->withErrors(['ordre' => 'Un trimestre avec cet ordre existe déjà pour cette année.'])
                         ->withInput();
        }

        $trimestre->update([
            'nom'     => $validated['nom'],
            'ordre'   => $validated['ordre'],
            'periode' => $validated['periode'] ?? null,
        ]);

        // Sync pivot (active = true)
        $trimestre->annees()->syncWithoutDetaching([
            $annee->id => ['active' => true]
        ]);

        return redirect()->route('trimestres.index')
                         ->with('success', 'Trimestre mis à jour avec succès.');
    }

    /* =======================
     * TOGGLE ACTIVE PIVOT
     * ======================= */
    public function toggleActive(Trimestre $trimestre, Annee $annee)
    {
        $pivot = $trimestre->annees()->where('annee_id', $annee->id)->first();

        if (!$pivot) {
            return back()->with('error', 'Relation inexistante.');
        }

        $trimestre->annees()->updateExistingPivot(
            $annee->id,
            ['active' => !$pivot->pivot->active]
        );

        return back()->with('success', 'Statut du trimestre modifié.');
    }

    /* =======================
     * API AJAX
     * ======================= */
public function getTrimestresByAnnee($anneeId)
{
    $annee = Annee::findOrFail($anneeId);

    $trimestres = $annee->trimestres()
        ->wherePivot('active', true)
        ->orderBy('ordre') // si existe
        ->get([
            'trimestres.id',
            'trimestres.nom',
           
        ]);

    return response()->json($trimestres);
}

public function create()
    {
        $annees = Annee::all();
        return view('trimestres.create', compact('annees'));
    }
}
