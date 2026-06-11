<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\AnneeClasse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneeApiController extends Controller
{
    /**
     * Liste des années scolaires
     */
    public function index()
    {
        $annees = Annee::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $annees
        ]);
    }

    /**
     * Enregistrer une année scolaire
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom'   => 'required|unique:annees,nom',
            'debut' => 'required|date',
            'fin'   => 'required|date|after:debut',
        ]);

        DB::transaction(function () use ($request, &$annee) {

            $annee = Annee::create([
                'nom'       => $request->nom,
                'debut'     => $request->debut,
                'fin'       => $request->fin,
                'en_cours'  => $request->boolean('en_cours'),
            ]);

            // Attacher automatiquement toutes les classes
            foreach (Classe::all() as $classe) {

                AnneeClasse::create([
                    'classe_id' => $classe->id,
                    'annee_id'  => $annee->id,
                    'en_cours'  => true,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Année scolaire créée avec succès',
            'data'    => $annee
        ], 201);
    }

    /**
     * Afficher une année
     */
    public function show(Annee $annee)
    {
        return response()->json([
            'success' => true,
            'data' => $annee
        ]);
    }

    /**
     * Mettre à jour une année
     */
    public function update(Request $request, Annee $annee)
    {
        $request->validate([
            'nom'       => 'required|string|unique:annees,nom,' . $annee->id,
            'debut'     => 'required|date',
            'fin'       => 'required|date|after:debut',
            'en_cours'  => 'nullable|boolean',
        ]);

        if ($request->boolean('en_cours')) {

            Annee::where('en_cours', true)
                ->where('id', '!=', $annee->id)
                ->update([
                    'en_cours' => false
                ]);
        }

        $annee->update([
            'nom'       => $request->nom,
            'debut'     => $request->debut,
            'fin'       => $request->fin,
            'en_cours'  => $request->boolean('en_cours'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Année mise à jour avec succès',
            'data'    => $annee
        ]);
    }

    /**
     * Supprimer une année
     */
    public function destroy(Annee $annee)
    {
        $annee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Année supprimée avec succès'
        ]);
    }

    /**
     * Classes actives d'une année
     */
    public function getClassesActives($anneeId)
    {
        $classes = Classe::orderBy('id')
            ->join('annee_classe', 'classes.id', '=', 'annee_classe.classe_id')
            ->where('annee_classe.annee_id', $anneeId)
            ->where('annee_classe.active', true)
            ->select(
                'classes.id',
                'classes.nom',
                'classes.niveau',
                'annee_classe.active'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Classes du cycle 3 pour une année
     */
    public function getClassesCycle3(Annee $annee)
    {
        $classes = Classe::where('cycle_id', 3)
            ->whereHas('annees', function ($q) use ($annee) {

                $q->where('annee_id', $annee->id);

            })
            ->orderBy('ordre')
            ->get([
                'id',
                'nom',
                'niveau',
                'cycle_id'
            ]);

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }
}