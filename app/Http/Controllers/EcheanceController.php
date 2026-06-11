<?php

namespace App\Http\Controllers;

use App\Models\Echeance;
use App\Models\Frais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcheanceController extends Controller
{
    public function index(Frais $frai)
    {
        $frai->load('echeances');

        return view('echeances.index', [
            'frais' => $frai
        ]);
    }

    public function create(Frais $frai)
    {
        return view('echeances.create', [
            'frais' => $frai
        ]);
    }

    public function store(Request $request, Frais $frai)
    {
        $request->validate([
            'echeances' => 'required|array|min:1',
            'echeances.*.nom' => 'required|string|max:255',
            'echeances.*.montant' => 'required|integer|min:0',
            'echeances.*.date_limite' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $frai) {
            foreach ($request->echeances as $echeance) {
                $frai->echeances()->create([
                    'nom'     => $echeance['nom'],
                    'montant'     => $echeance['montant'],
                    'date_limite' => $echeance['date_limite'],
                ]);
            }
        });

        return redirect()
            ->route('frais.echeances.index', $frai->id)
            ->with('success', 'Échéances enregistrées avec succès.');
    }

    public function show(Frais $frai, Echeance $echeance)
    {
        $this->verifierAppartenance($frai, $echeance);

        return view('echeances.show', [
            'frais' => $frai,
            'echeance' => $echeance
        ]);
    }

    public function edit(Frais $frai, Echeance $echeance)
    {
        $this->verifierAppartenance($frai, $echeance);

        return view('echeances.edit', [
            'frais' => $frai,
            'echeance' => $echeance
        ]);
    }

    public function update(Request $request, Frais $frai, Echeance $echeance)
    {
        $this->verifierAppartenance($frai, $echeance);

        $request->validate([
            'nom' => 'required|string|max:255',
            'montant' => 'required|integer|min:0',
            'date_limite' => 'required|date',
        ]);

        $echeance->update($request->only([
            'nom',
            'montant',
            'date_limite'
        ]));

        return redirect()
            ->route('frais.echeances.index', $frai->id)
            ->with('success', 'Échéance modifiée avec succès.');
    }

    public function destroy(Frais $frai, Echeance $echeance)
    {
        $this->verifierAppartenance($frai, $echeance);

        $echeance->delete();

        return redirect()
            ->route('frais.echeances.index', $frai->id)
            ->with('success', 'Échéance supprimée avec succès.');
    }

    /**
     * Sécurité : empêche d’accéder à une échéance qui n’appartient pas au frais
     */
    private function verifierAppartenance(Frais $frai, Echeance $echeance)
    {
        if ($echeance->frais_id !== $frai->id) {
            abort(404);
        }
    }
}
