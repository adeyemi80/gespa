<?php

namespace App\Http\Controllers;

use App\Models\TdTarif;
use App\Models\Annee;
use Illuminate\Http\Request;

class TdTarifController extends Controller
{
    public function index(Request $request)
    {
        $annees       = Annee::orderByDesc('id')->get();
        $anneeEnCours = Annee::where('en_cours', true)->first();

        $anneeId = $request->annee_id
            ?? $anneeEnCours?->id
            ?? $annees->first()?->id;

        $tarifs = TdTarif::with('annee')
            ->when($anneeId,           fn($q) => $q->where('annee_id',  $anneeId))
            ->when($request->categorie, fn($q) => $q->where('categorie', $request->categorie))
            ->when($request->type,      fn($q) => $q->where('type',      $request->type))
            ->orderBy('categorie')
            ->orderBy('type')
            ->paginate(20)
            ->withQueryString();

        return view('td_tarifs.index', compact(
            'tarifs', 'annees', 'anneeEnCours', 'anneeId'
        ));
    }

    public function create()
    {
        $annees       = Annee::orderByDesc('id')->get();
        $anneeEnCours = Annee::where('en_cours', true)->first();

        return view('td_tarifs.create', compact('annees', 'anneeEnCours'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'annee_id'  => 'required|exists:annees,id',
            'categorie' => 'required|string|max:100',
            'type'      => 'required|in:seance,mois,annee',
            'montant'   => 'required|numeric|min:0',
        ]);

        try {
            TdTarif::create($data);

            return redirect()
                ->route('td-tarifs.index')
                ->with('success', 'Tarif créé avec succès.');

        } catch (\Illuminate\Database\QueryException $e) {
            return back()
                ->withInput()
                ->with('error', 'Un tarif existe déjà pour cette combinaison année / catégorie / type.');
        }
    }

    public function show(TdTarif $tdTarif)
    {
        $tdTarif->load('annee');

        return view('td_tarifs.show', compact('tdTarif'));
    }

    public function edit(TdTarif $tdTarif)
    {
        $annees       = Annee::orderByDesc('id')->get();
        $anneeEnCours = Annee::where('en_cours', true)->first();

        return view('td_tarifs.edit', compact('tdTarif', 'annees', 'anneeEnCours'));
    }

    public function update(Request $request, TdTarif $tdTarif)
    {
        $data = $request->validate([
            'annee_id'  => 'required|exists:annees,id',
            'categorie' => 'required|string|max:100',
            'type'      => 'required|in:seance,mois,annee',
            'montant'   => 'required|numeric|min:0',
        ]);

        try {
            $tdTarif->update($data);

            return redirect()
                ->route('td-tarifs.index')
                ->with('success', 'Tarif modifié avec succès.');

        } catch (\Illuminate\Database\QueryException $e) {
            return back()
                ->withInput()
                ->with('error', 'Un tarif existe déjà pour cette combinaison année / catégorie / type.');
        }
    }

    public function destroy(TdTarif $tdTarif)
    {
        $tdTarif->delete();

        return redirect()
            ->route('td-tarifs.index')
            ->with('success', 'Tarif supprimé avec succès.');
    }
}