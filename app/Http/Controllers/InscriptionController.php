<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Note;
use App\Models\Moyenne;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function index(Request $request)
{
    $cycles = Cycle::all();
    $annees = Annee::all();
    $classes = Classe::all();

    $query = Inscription::orderBy('id')->with(['eleve', 'classe', 'annee'])->latest();

    // Filtre par cycle (via classe → cycle)
    if ($request->filled('cycle_id')) {
        $query->whereHas('classe', function ($q) use ($request) {
            $q->where('cycle_id', $request->cycle_id);
        });
    }

    // Filtre par classe
    if ($request->filled('classe_id')) {
        $query->where('classe_id', $request->classe_id);
    }

    // Filtre par année
    if ($request->filled('annee_id')) {
        $query->where('annee_id', $request->annee_id);
    }

    $inscriptions = $query->paginate(50);

    return view('inscriptions.index', compact(
        'inscriptions', 'cycles', 'annees', 'classes'
    ));
}

    public function create()
    {
        return view('inscriptions.create', [
            'eleves'  => Eleve::all(),
            'classes' => Classe::all(),
            'annees'  => Annee::all(),
            'notes'   => Note::all(),
        ]);
    }

    public function store(Request $request)
    {
        // 1️⃣ Validation
        $request->validate([
            'eleve_id'  => 'required|exists:eleves,id',
            'classe_id' => 'required|exists:classes,id',
            'annee_id'  => 'required|exists:annees,id',
            'note_id'   => 'required|exists:notes,id',
        ]);

        // 2️⃣ Pas de double inscription la même année
        $existe = Inscription::where('eleve_id', $request->eleve_id)
            ->where('annee_id', $request->annee_id)
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'eleve_id' => 'Cet élève est déjà inscrit pour cette année scolaire.'
            ])->withInput();
        }

        // 3️⃣ Récupération moyenne
        $moyenne = Moyenne::where('eleve_id', $request->eleve_id)
            ->where('classe_id', $request->classe_id)
            ->where('annee_id', $request->annee_id)
            ->value('moyenne_generale');

        if (is_null($moyenne)) {
            return back()->withErrors([
                'moyenne' => 'La moyenne annuelle n’est pas encore calculée.'
            ])->withInput();
        }

        $decision = $moyenne >= 10 ? 'passé' : 'redoublé';

        // 4️⃣ Transaction globale
        DB::transaction(function () use ($request, $moyenne, $decision) {

            // ➕ Création inscription
            $inscription = Inscription::create([
                'eleve_id'         => $request->eleve_id,
                'classe_id'        => $request->classe_id,
                'annee_id'         => $request->annee_id,
                'note_id'          => $request->note_id,
                'moyenne_annuelle' => $moyenne,
                'decision'         => $decision,
                'date_inscription' => now(),
            ]);

            // ➕ Génération automatique des frais
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
        });

        return redirect()
            ->route('inscriptions.index')
            ->with('success', 'Inscription créée avec frais générés automatiquement.');
    }

    public function show(Inscription $inscription)
    {
        return view('inscriptions.show', compact('inscription'));
    }

    public function edit(Inscription $inscription)
    {
        return view('inscriptions.edit', [
            'inscription' => $inscription,
            'eleves'      => Eleve::all(),
            'classes'     => Classe::all(),
            'annees'      => Annee::all(),
            'notes'       => Note::all(),
        ]);
    }

    public function update(Request $request, Inscription $inscription)
{
    $request->validate([
        'eleve_id'         => 'required|exists:eleves,id',
        'classe_id'        => 'required|exists:classes,id',
        'annee_id'         => 'required|exists:annees,id',
        'note_id'          => 'required|exists:notes,id',
        'date_inscription' => 'required|date',
    ]);

    $inscription->update($request->only([
        'eleve_id',
        'classe_id',
        'annee_id',
        'note_id',
        'date_inscription',
    ]));

    // ➕ Génération automatique des frais manquants
    // (uniquement les frais pas encore affectés à cette inscription)
    $classe = Classe::with('frais')->findOrFail($request->classe_id);

    $fraisExistants = DB::table('inscription_frais')
        ->where('inscription_id', $inscription->id)
        ->pluck('frais_id')
        ->toArray();

    foreach ($classe->frais as $frais) {
        if (in_array($frais->id, $fraisExistants)) {
            continue; // ← évite les doublons si les frais existent déjà
        }

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

    return redirect()
        ->route('inscriptions.index')
        ->with('success', 'Inscription mise à jour avec succès.');
}

    public function destroy(Inscription $inscription)
    {
        $inscription->delete();

        return redirect()
            ->route('inscriptions.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }


}
