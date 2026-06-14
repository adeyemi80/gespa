<?php

namespace App\Http\Controllers;

use App\Models\TdSeance;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use Illuminate\Http\Request;

class TdSeanceController extends Controller
{
    public function index(Request $request)
{
    $annees       = Annee::orderByDesc('id')->get();
    $cycles       = Cycle::orderBy('id')->get();
    $classes      = Classe::orderBy('niveau')->get();
    $anneeEnCours = Annee::where('en_cours', true)->first();

    // Année par défaut : request > en_cours > première
    $anneeId = $request->annee_id
        ?? $anneeEnCours?->id
        ?? $annees->first()?->id;

    $seances = TdSeance::with(['annee', 'classe'])
        ->when($anneeId,            fn($q) => $q->where('annee_id',  $anneeId))
        ->when($request->classe_id, fn($q) => $q->where('classe_id', $request->classe_id))
        ->when($request->date,      fn($q) => $q->where('date',      $request->date))
        ->orderByDesc('date')
        ->paginate(20)
        ->withQueryString();

    return view('td_seances.index', compact(
        'seances', 'annees', 'cycles', 'classes', 'anneeEnCours', 'anneeId'
    ));
}

   public function create()
{
    $annees     = Annee::orderByDesc('id')->get();
    $cycles     = Cycle::orderBy('id')->get();
    $classes    = Classe::orderBy('niveau')->get();
    $anneeEnCours = Annee::where('en_cours', true)->first();

    return view('td_seances.create', compact('annees', 'cycles', 'classes', 'anneeEnCours'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'date'      => 'required|date',
            'libelle'   => 'nullable|string|max:255',
        ]);

        try {
            TdSeance::create($data);

            return redirect()
                ->route('td-seances.index')
                ->with('success', 'Séance créée avec succès.');

        } catch (\Illuminate\Database\QueryException $e) {
            return back()
                ->withInput()
                ->with('error', 'Une séance existe déjà pour cette classe à cette date.');
        }
    }

    public function show(TdSeance $tdSeance)
    {
        $tdSeance->load(['annee', 'classe']);

        return view('td_seances.show', compact('tdSeance'));
    }

    public function edit(TdSeance $tdSeance)
    {
        $annees  = Annee::orderByDesc('id')->get();
        $classes = Classe::orderBy('niveau')->get();

        return view('td_seances.edit', compact('tdSeance', 'annees', 'classes'));
    }

    public function update(Request $request, TdSeance $tdSeance)
    {
        $data = $request->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'date'      => 'required|date',
            'libelle'   => 'nullable|string|max:255',
        ]);

        try {
            $tdSeance->update($data);

            return redirect()
                ->route('td-seances.index')
                ->with('success', 'Séance modifiée avec succès.');

        } catch (\Illuminate\Database\QueryException $e) {
            return back()
                ->withInput()
                ->with('error', 'Une séance existe déjà pour cette classe à cette date.');
        }
    }

    public function destroy(TdSeance $tdSeance)
    {
        $tdSeance->delete();

        return redirect()
            ->route('td-seances.index')
            ->with('success', 'Séance supprimée avec succès.');
    }
}