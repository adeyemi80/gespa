<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Enseignement;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnseignementController extends Controller
{
    /**
     * Liste des affectations pédagogiques.
     */
    public function index()
    {

        return view('enseignements.index');
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $annees = Annee::orderByDesc('id')->get();

        $classes = Classe::orderBy('ordre')
            ->orderBy('nom')
            ->get();

        $matieres = Matiere::orderBy('nom')->get();

        $enseignants = Enseignant::with('user')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('enseignements.create', compact(
            'annees',
            'classes',
            'matieres',
            'enseignants'
        ));
    }

    /**
     * Enregistre une nouvelle affectation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee_id' => [
                'required',
                'integer',
                'exists:annees,id',
            ],

            'classe_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'matiere_id' => [
                'required',
                'integer',
                'exists:matieres,id',
            ],

            'enseignant_id' => [
                'required',
                'integer',
                'exists:enseignants,id',
            ],

            'coefficient' => [
                'required',
                'numeric',
                'min:0',
            ],

            'volume_horaire' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'heures_par_semaine' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],
        ], [
            'annee_id.required' => 'Veuillez sélectionner une année scolaire.',
            'annee_id.exists' => 'L’année scolaire sélectionnée est invalide.',

            'classe_id.required' => 'Veuillez sélectionner une classe.',
            'classe_id.exists' => 'La classe sélectionnée est invalide.',

            'matiere_id.required' => 'Veuillez sélectionner une matière.',
            'matiere_id.exists' => 'La matière sélectionnée est invalide.',

            'enseignant_id.required' => 'Veuillez sélectionner un enseignant.',
            'enseignant_id.exists' => 'L’enseignant sélectionné est invalide.',

            'coefficient.required' => 'Veuillez renseigner le coefficient.',
        ]);

        /*
         * Vérification métier :
         * une matière ne peut avoir qu'un seul enseignant
         * dans une même classe pour une même année.
         */
        $existe = Enseignement::where('annee_id', $validated['annee_id'])
            ->where('classe_id', $validated['classe_id'])
            ->where('matiere_id', $validated['matiere_id'])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->with('error', 'Cette matière est déjà affectée à cette classe pour cette année scolaire.');
        }

        $validated['actif'] = $request->boolean('actif', true);

        Enseignement::create($validated);

        return redirect()
            ->route('enseignements.index')
            ->with('success', 'L’affectation pédagogique a été enregistrée avec succès.');
    }

    /**
     * Affiche une affectation.
     */
    public function show(Enseignement $enseignement)
    {
        $enseignement->load([
            'annee',
            'classe',
            'matiere',
            'enseignant.user',
        ]);

        return view('enseignements.show', compact('enseignement'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Enseignement $enseignement)
    {
        $annees = Annee::orderByDesc('id')->get();

        $classes = Classe::orderBy('ordre')
            ->orderBy('nom')
            ->get();

        $matieres = Matiere::orderBy('nom')->get();

        $enseignants = Enseignant::with('user')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('enseignements.edit', compact(
            'enseignement',
            'annees',
            'classes',
            'matieres',
            'enseignants'
        ));
    }

    /**
     * Met à jour une affectation.
     */
    public function update(Request $request, Enseignement $enseignement)
    {
        $validated = $request->validate([
            'annee_id' => [
                'required',
                'integer',
                'exists:annees,id',
            ],

            'classe_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'matiere_id' => [
                'required',
                'integer',
                'exists:matieres,id',
            ],

            'enseignant_id' => [
                'required',
                'integer',
                'exists:enseignants,id',
            ],

            'coefficient' => [
                'required',
                'numeric',
                'min:0',
            ],

            'volume_horaire' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'heures_par_semaine' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Vérifie qu'une autre affectation n'utilise pas
         * déjà le même couple année + classe + matière.
         */
        $existe = Enseignement::where('annee_id', $validated['annee_id'])
            ->where('classe_id', $validated['classe_id'])
            ->where('matiere_id', $validated['matiere_id'])
            ->where('id', '!=', $enseignement->id)
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->with('error', 'Cette matière est déjà affectée à cette classe pour cette année scolaire.');
        }

        $validated['actif'] = $request->boolean('actif', false);

        $enseignement->update($validated);

        return redirect()
            ->route('enseignements.index')
            ->with('success', 'L’affectation pédagogique a été modifiée avec succès.');
    }

    /**
     * Supprime une affectation.
     */
    public function destroy(Enseignement $enseignement)
    {
        $enseignement->delete();

        return redirect()
            ->route('enseignements.index')
            ->with('success', 'L’affectation pédagogique a été supprimée avec succès.');
    }

    /**
     * Active une affectation.
     */
    public function activer(Enseignement $enseignement)
    {
        $enseignement->update([
            'actif' => true,
        ]);

        return back()->with(
            'success',
            'L’affectation pédagogique a été activée.'
        );
    }

    /**
     * Désactive une affectation.
     */
    public function desactiver(Enseignement $enseignement)
    {
        $enseignement->update([
            'actif' => false,
        ]);

        return back()->with(
            'success',
            'L’affectation pédagogique a été désactivée.'
        );
    }

    /**
     * Retourne les classes d'une année.
     * Utilisé pour les sélections dynamiques AJAX.
     */
    public function classesParAnnee($anneeId)
    {
        $classes = Classe::whereHas('annees', function ($query) use ($anneeId) {
            $query->where('annees.id', $anneeId);
        })
            ->orderBy('ordre')
            ->orderBy('nom')
            ->get([
                'id',
                'nom',
                'niveau',
                'cycle_id',
                'ordre',
            ]);

        return response()->json($classes);
    }

    /**
     * Retourne les matières d'une classe.
     */
    public function matieresParClasse($classeId)
    {
        $classe = Classe::findOrFail($classeId);

        /*
         * Si ton modèle Classe possède déjà une relation matieres(),
         * cette méthode récupère automatiquement les matières
         * associées à la classe.
         */
        $matieres = $classe->matieres()
            ->orderBy('nom')
            ->get();

        return response()->json($matieres);
    }

    /**
     * Retourne les enseignants affectés à une année/classe.
     */
    public function enseignantsParClasse($anneeId, $classeId)
    {
        $enseignants = Enseignant::whereHas('enseignements', function ($query) use (
            $anneeId,
            $classeId
        ) {
            $query->where('annee_id', $anneeId)
                ->where('classe_id', $classeId)
                ->where('actif', true);
        })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return response()->json($enseignants);
    }
}