<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Frais;
use App\Models\Eleve;
use App\Models\Echeance;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Annee;
use App\Models\Inscription;
use App\Models\InscriptionFrais;
use App\Models\AnneeClasseFrais;
use Illuminate\Support\Facades\DB;

class FraisController extends Controller
{
    /**
     * Liste des frais
     */
  public function index(Request $request)
{
    $cycles = Cycle::all();
    $annees = Annee::all();
    $classes = Classe::all();

    $query = Frais::with([
        'echeances',
        'anneeClasseFrais.classe',
        'anneeClasseFrais.annee',
    ]);

    // Filtre par classe
    if ($request->filled('classe_id')) {
        $query->whereHas('anneeClasseFrais', function ($q) use ($request) {
            $q->where('classe_id', $request->classe_id);
        });
    }

    // Filtre par année
    if ($request->filled('annee_id')) {
        $query->whereHas('anneeClasseFrais', function ($q) use ($request) {
            $q->where('annee_id', $request->annee_id);
        });
    }

    // Filtre par cycle (via la relation classe → cycle)
    if ($request->filled('cycle_id')) {
        $query->whereHas('anneeClasseFrais.classe', function ($q) use ($request) {
            $q->where('cycle_id', $request->cycle_id);
        });
    }

    $frais = $query->get();

    return view('frais.index', compact('frais', 'classes', 'annees', 'cycles'));
}

    /**
     * Formulaire création
     */
    public function create()
    {
        $classes = Classe::orderByNiveau()->get();
        $annees  = Annee::all();
        $cycles  = Cycle::all();

        return view('frais.create', compact(
            'classes',
            'annees',
            'cycles'
        ));
    }

    /**
     * Enregistrement
     */
   public function store(Request $request)
{
    $request->validate([
        'nom'                     => 'required|string|max:255',
        'description'             => 'nullable|string',
        'montant'                 => 'required|numeric|min:0',
        'classe_id'               => 'required|exists:classes,id',
        'annee_id'                => 'required|exists:annees,id',
        'echeances'               => 'required|array|min:1',
        'echeances.*.libelle'     => 'required|string|max:255',
        'echeances.*.montant'     => 'required|numeric|min:0',
        'echeances.*.date_limite' => 'required|date',
    ]);

    // ✅ Unicité : même nom + même classe + même année
    $doublon = Frais::where('nom', $request->nom)
        ->whereHas('anneeClasseFrais', function ($q) use ($request) {
            $q->where('classe_id', $request->classe_id)
              ->where('annee_id',  $request->annee_id);
        })
        ->exists();

    if ($doublon) {
        return back()->withInput()->withErrors([
            'nom' => "Le frais « {$request->nom} » existe déjà pour cette classe et cette année.",
        ]);
    }

    DB::transaction(function () use ($request) {

        $frais = Frais::create([
            'nom'         => $request->nom,
            'description' => $request->description,
        ]);

        AnneeClasseFrais::updateOrCreate(
            [
                'annee_id'  => $request->annee_id,
                'classe_id' => $request->classe_id,
                'frais_id'  => $frais->id,
            ],
            [
                'montant' => $request->montant,
            ]
        );

        $this->saveEcheances($request, $frais);
        $this->saveInscriptionFrais($request, $frais);
    });

    return redirect()->route('frais.create')
        ->with('success', '✅ Frais créé avec succès.');
}

public function update(Request $request, Frais $frais)
{
    $request->validate([
        'nom'                     => 'required|string|max:255',
        'description'             => 'nullable|string',
        'montant'                 => 'required|numeric|min:0',
        'classe_id'               => 'required|exists:classes,id',
        'annee_id'                => 'required|exists:annees,id',
        'echeances'               => 'required|array|min:1',
        'echeances.*.libelle'     => 'required|string|max:255',
        'echeances.*.montant'     => 'required|numeric|min:0',
        'echeances.*.date_limite' => 'required|date',
    ]);

    // ✅ Même vérification en excluant le frais en cours de modification
    $doublon = Frais::where('nom', $request->nom)
        ->where('id', '!=', $frais->id)
        ->whereHas('anneeClasseFrais', function ($q) use ($request) {
            $q->where('classe_id', $request->classe_id)
              ->where('annee_id',  $request->annee_id);
        })
        ->exists();

    if ($doublon) {
        return back()->withInput()->withErrors([
            'nom' => "Le frais « {$request->nom} » existe déjà pour cette classe et cette année.",
        ]);
    }

    DB::transaction(function () use ($request, $frais) {

        $frais->update([
            'nom'         => $request->nom,
            'description' => $request->description,
        ]);

        AnneeClasseFrais::updateOrCreate(
            [
                'annee_id'  => $request->annee_id,
                'classe_id' => $request->classe_id,
                'frais_id'  => $frais->id,
            ],
            [
                'montant' => $request->montant,
            ]
        );

        $this->saveEcheances($request, $frais, true);
        $this->saveInscriptionFrais($request, $frais);
    });

    return redirect()->route('frais.index')
        ->with('success', '✅ Frais mis à jour avec succès.');
}

    /**
     * Sauvegarde échéances
     */
    private function saveEcheances(
        Request $request,
        Frais $frais,
        $deleteOld = false
    ) {
        if ($deleteOld) {
            $frais->echeances()->delete();
        }

        foreach ($request->echeances as $echeance) {

            Echeance::create([
                'frais_id' => $frais->id,
                'nom' => $echeance['libelle'],
                'montant' => $echeance['montant'],
                'date_limite' => $echeance['date_limite'],
                'classe_id' => $request->classe_id,
                'annee_id' => $request->annee_id,
            ]);
        }
    }

    /**
     * Création inscription_frais
     */
    private function saveInscriptionFrais(
        Request $request,
        Frais $frais
    ) {

        $inscriptions = Inscription::where(
            'classe_id',
            $request->classe_id
        )
        ->where(
            'annee_id',
            $request->annee_id
        )
        ->get();

        foreach ($inscriptions as $inscription) {

            $inscriptionFrais = InscriptionFrais::firstOrNew([

                'inscription_id' => $inscription->id,
                'frais_id' => $frais->id,
                'annee_id' => $request->annee_id,
            ]);

            $inscriptionFrais->montant_frais = $request->montant;

            $montantPaye = $inscriptionFrais->montant_paye ?? 0;

            $inscriptionFrais->reste =
                $request->montant - $montantPaye;

            $inscriptionFrais->statut =
                $montantPaye >= $request->montant
                ? 'soldé'
                : 'non_payé';

            $inscriptionFrais->est_arriere = false;

            $inscriptionFrais->save();
        }
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Frais::with(['echeances']);

        if ($request->filled('classe_id')) {

            $query->whereHas('anneeClasseFrais', function ($q) use ($request) {
                $q->where('classe_id', $request->classe_id);
            });
        }

        if ($request->filled('annee_id')) {

            $query->whereHas('anneeClasseFrais', function ($q) use ($request) {
                $q->where('annee_id', $request->annee_id);
            });
        }

        $frais = $query->get();

        $pdf = PDF::loadView('frais.pdf', compact('frais'));

        return $pdf->download('frais_filtres.pdf');
    }

    /**
     * Liste par classe
     */
    public function listeParClasse(Request $request)
    {
        $classe_id = $request->classe_id;

        $classes = Classe::all();

        $classe = null;
        $eleves = collect();
        $frais  = collect();

        if ($classe_id) {

            $classe = Classe::find($classe_id);

            if ($classe) {

                $eleves = Eleve::where(
                    'classe_id',
                    $classe_id
                )->get();

                $frais = Frais::whereHas(
                    'anneeClasseFrais',
                    function ($q) use ($classe_id) {
                        $q->where('classe_id', $classe_id);
                    }
                )->with('echeances')->get();
            }
        }

        return view('frais.classe', compact(
            'classes',
            'classe_id',
            'classe',
            'eleves',
            'frais'
        ));
    }

    /**
     * Détails élève
     */
    public function detailsEleve(Eleve $eleve)
    {
        $frais = Frais::whereHas(
            'anneeClasseFrais',
            function ($q) use ($eleve) {
                $q->where('classe_id', $eleve->classe_id);
            }
        )
        ->with('echeances')
        ->get();

        $echeances = Echeance::whereIn(
            'frais_id',
            $frais->pluck('id')
        )->get();

        return view('frais.details', compact(
            'eleve',
            'frais',
            'echeances'
        ));
    }

    /**
     * Affichage
     */
    public function show(Frais $frais)
    {
        $frais->load([
            'echeances',
            'anneeClasseFrais'
        ]);

        return view('frais.show', compact('frais'));
    }

    /**
     * Édition
     */
    public function edit(Frais $frais)
    {
        $frais->load([
            'echeances',
            'anneeClasseFrais'
        ]);

        $classes = Classe::all();
        $annees  = Annee::all();

        return view('frais.edit', compact(
            'frais',
            'classes',
            'annees'
        ));
    }

    /**
     * Suppression
     */
    public function destroy(Frais $frais)
    {
        $frais->echeances()->delete();

        AnneeClasseFrais::where(
            'frais_id',
            $frais->id
        )->delete();

        $frais->delete();

        return redirect()
            ->route('frais.index')
            ->with(
                'success',
                '🗑️ Frais supprimé avec succès.'
            );
    }
}