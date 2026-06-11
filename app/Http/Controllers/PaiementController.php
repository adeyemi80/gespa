<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Frais;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Inscription;
use App\Models\InscriptionFrais;
use App\Models\Annee;
use App\Models\Recette;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PaiementController extends Controller
{

public function index(Request $request)
{
    // ======================
    // ANNÉES
    // ======================
    $annees = Annee::orderBy('nom')->get();

    // ======================
    // CYCLES (filtrés par année si sélectionnée)
    // ======================
    $cyclesQuery = Cycle::query();
    if ($request->filled('annee_id') && is_numeric($request->annee_id)) {
        $cyclesQuery->whereHas('classes.inscriptions', function($q) use ($request) {
            $q->where('annee_id', (int) $request->annee_id);
        });
    }
    $cycles = $cyclesQuery->orderBy('nom')->get();

    // ======================
    // CLASSES (filtrées par année + cycle)
    // ======================
    $classesQuery = Classe::query();
    if ($request->filled('annee_id') && is_numeric($request->annee_id)) {
        $classesQuery->whereHas('inscriptions', function ($q) use ($request) {
            $q->where('annee_id', (int) $request->annee_id);
        });
    }
    if ($request->filled('cycle_id') && is_numeric($request->cycle_id)) {
        $classesQuery->where('cycle_id', (int) $request->cycle_id);
    }
    $classes = $classesQuery->orderBy('nom')->get();

    // ======================
    // FRAIS (selon classe)
    // ======================
    $frais = collect();
    if ($request->filled('classe_id') && is_numeric($request->classe_id)) {
        $frais = Frais::whereHas('paiements.inscription', function ($q) use ($request) {
            $q->where('classe_id', (int) $request->classe_id);
        })->orderBy('description')->get();
    }

    // ======================
    // INSCRIPTIONS/ÉLÈVES (annee + classe)
    // ======================
    $inscriptions = collect();
    if ($request->filled('annee_id') && is_numeric($request->annee_id) 
        && $request->filled('classe_id') && is_numeric($request->classe_id)) {
        $inscriptions = Inscription::with(['eleve:id,nom,prenom'])
            ->where('annee_id', (int) $request->annee_id)
            ->where('classe_id', (int) $request->classe_id)
            ->orderBy('eleve_id')
            ->get();
    }

    // ======================
    // PAIEMENTS (PAGINATION + FILTRES COMPLETS)
    // ======================
    $query = Paiement::with([
        'inscription.eleve:id,id,nom,prenom',
        'inscription.classe:id,nom',
        'inscription.annee:id,nom',
        'frais:id,description'
    ]);

    // Filtrage paiements : annee → cycle → classe → eleve → frais
    if ($request->filled('annee_id') && is_numeric($request->annee_id)) {
        $query->whereHas('inscription', function ($q) use ($request) {
            $q->where('annee_id', (int) $request->annee_id);
        });
    }
    
    if ($request->filled('cycle_id') && is_numeric($request->cycle_id)) {
        $query->whereHas('inscription.classe', function ($q) use ($request) {
            $q->where('cycle_id', (int) $request->cycle_id);
        });
    }

    if ($request->filled('classe_id') && is_numeric($request->classe_id)) {
        $query->whereHas('inscription', function ($q) use ($request) {
            $q->where('classe_id', (int) $request->classe_id);
        });
    }

    if ($request->filled('inscription_id') && is_numeric($request->inscription_id)) {
        $query->where('inscription_id', (int) $request->inscription_id);
    }

    if ($request->filled('frais_id') && is_numeric($request->frais_id)) {
        $query->where('frais_id', (int) $request->frais_id);
    }

    if ($request->filled('date_debut')) {
        $query->whereDate('date_paiement', '>=', $request->date_debut);
    }

    if ($request->filled('date_fin')) {
        $query->whereDate('date_paiement', '<=', $request->date_fin);
    }

    // 🔥 PAGINATION NATIVE
    $paiements = $query->orderByDesc('id')->paginate(25);

    // ======================
    // RETURN VIEW
    // ======================
    return view('paiements.index', compact(
        'annees', 'cycles', 'classes', 'frais', 
        'inscriptions', 'paiements'
    ));
}
    /**
     * ✅ AJAX : inscriptions filtrées par CLASSE + ANNÉE
     */
    public function getInscriptionsParClasse(Request $request, $classeId)
    {
        $request->validate([
            'annee_id' => 'required|exists:annees,id'
        ]);

        $inscriptions = Inscription::with('eleve')
            ->where('classe_id', $classeId)
            ->where('annee_id', $request->annee_id)
            ->get();

        return response()->json($inscriptions);
    }

    public function create()
{
   // Récupérer toutes les années
    $annees = Annee::all();

    // Récupérer l'année en cours
    $anneeEnCours = Annee::where('en_cours', 't')->first();
     $cycles = Cycle ::all();
    $classes = Classe::orderByNiveau()->get();
    $frais = Frais::all();

    return view('paiements.create', compact('annees', 'anneeEnCours', 'classes', 'frais', 'cycles'));
}
public function paiement()
{
    return view('paiements.paiement');
}
public function fraisFilter()
{
    return view('paiements.fraisFilter');
}

public function store(Request $request)
{
    $request->validate([
        'annee_id'       => 'required|exists:annees,id',
        'inscription_id' => 'required|exists:inscriptions,id',
        'frais_id'       => 'required|exists:frais,id',
        'montant_verse'  => 'required|numeric|min:1',
        'mode_paiement'  => 'required|string',
        'date_paiement'  => 'required|date',
    ]);

    // Vérifie l'inscription pour l'année
    $inscription = Inscription::where('id', $request->inscription_id)
        ->where('annee_id', $request->annee_id)
        ->first();

    if (!$inscription) {
        return back()->withErrors("❌ Inscription invalide pour cette année.");
    }

    DB::beginTransaction();
    try {
        // Récupère la ligne de frais
        $ligne = DB::table('inscription_frais')
            ->where('inscription_id', $inscription->id)
            ->where('frais_id', $request->frais_id)
            ->where('annee_id', $request->annee_id)
            ->lockForUpdate()
            ->first();

        if (!$ligne) {
            return back()->withErrors("❌ Aucune ligne de frais trouvée pour cet élève. Veuillez initialiser les frais d'abord.");
        }

        // Vérifie que le montant ne dépasse pas le reste
        if ($request->montant_verse > $ligne->reste) {
            return back()->withErrors("❌ Montant versé supérieur au reste à payer.");
        }

        // Crée le paiement
        $paiement = Paiement::create([
            'inscription_id' => $inscription->id,
            'frais_id'       => $request->frais_id,
            'montant_verse'  => $request->montant_verse,
            'mode_paiement'  => $request->mode_paiement,
            'date_paiement'  => $request->date_paiement,
            'numero_recu'    => 'REC-' . now()->format('Ymd') . '-' .
                str_pad((Paiement::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT),
        ]);

        // Mise à jour de la ligne inscription_frais
        $nouveauPaye = $ligne->montant_paye + $request->montant_verse;
        $reste = $ligne->montant_frais - $nouveauPaye;

        DB::table('inscription_frais')
            ->where('id', $ligne->id)
            ->update([
                'montant_paye' => $nouveauPaye,
                'reste'        => $reste,
                'statut'       => $reste == 0 ? 'soldé' : 'partiellement_payé',
                'updated_at'   => now(),
            ]);

        // Enregistre une recette
        Recette::create([
            'paiement_id'    => $paiement->id,
            'inscription_id' => $inscription->id,
            'montant_verse'  => $paiement->montant_verse,
            'date_paiement'  => $paiement->date_paiement,
            'mode_paiement'  => $paiement->mode_paiement,
            'numero_recu'    => $paiement->numero_recu,
        ]);

        DB::commit();

        // Récupère la ligne mise à jour pour le reçu
        $ligne = DB::table('inscription_frais')
            ->where('inscription_id', $inscription->id)
            ->where('frais_id', $request->frais_id)
            ->where('annee_id', $request->annee_id)
            ->first();

        // Retourne vers le reçu dans une nouvelle fenêtre
        return response()->view('paiements.recu', compact('paiement', 'ligne'));

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors("❌ Erreur lors de l'enregistrement: " . $e->getMessage());
    }
}    

    public function update(Request $request, $id)
{
    //dd($request->all());
    $request->validate([
        'inscription_id' => 'required|exists:inscriptions,id',
        'frais_id'       => 'required|exists:frais,id',
        'montant_verse'  => 'required|numeric|min:1',
        'mode_paiement'  => 'required|string',
        'date_paiement'  => 'required|date',
        //'annee_id'       => 'required|exists:annees,id',
    ]);

    $paiement = Paiement::findOrFail($id);

    // 🔐 Sécurité année
    $inscription = Inscription::where('id', $request->inscription_id)
        ->where('annee_id', $request->annee_id)
        ->first();

    if (!$inscription) {
        return back()->withErrors("❌ Inscription invalide pour cette année.");
    }

    DB::transaction(function () use ($request, $inscription, $paiement) {

        // Récupère la ligne actuelle d'inscription_frais
        $ligne = DB::table('inscription_frais')
            ->where('inscription_id', $inscription->id)
            ->where('frais_id', $request->frais_id)
            ->where('annee_id', $request->annee_id)
            ->lockForUpdate()
            ->first();

        if (!$ligne) {
            throw new \Exception("Ce frais n’est pas initialisé.");
        }

        // Calculer le nouveau montant payé en tenant compte de l'ancien paiement
        $ancienMontant = $paiement->montant_verse;
        $nouveauPaye = $ligne->montant_paye - $ancienMontant + $request->montant_verse;

        if ($nouveauPaye > $ligne->montant_frais) {
            throw new \Exception("Montant supérieur au reste à payer.");
        }

        $reste = $ligne->montant_frais - $nouveauPaye;

        // Mettre à jour le paiement
        $paiement->update([
            'montant_verse' => $request->montant_verse,
            'mode_paiement' => $request->mode_paiement,
            'date_paiement' => $request->date_paiement,
        ]);

        // Mettre à jour la table inscription_frais
        DB::table('inscription_frais')
            ->where('id', $ligne->id)
            ->update([
                'montant_paye' => $nouveauPaye,
                'reste'        => $reste,
                'statut'       => $reste == 0 ? 'soldé' : 'partiellement_payé',
                'updated_at'   => now(),
            ]);

        // Mettre à jour la recette associée
        $recette = Recette::where('paiement_id', $paiement->id)->first();
        if ($recette) {
            $recette->update([
                'montant_verse' => $paiement->montant_verse,
                'date_paiement' => $paiement->date_paiement,
                'mode_paiement' => $paiement->mode_paiement,
            ]);
        }
    });

    return back()->with('success', '✅ Paiement mis à jour avec succès.');
}

    /**
     * ✅ Frais filtrés par INSCRIPTION + ANNÉE
     */
    public function getFraisParInscription($inscriptionId, Request $request)
{
    
    $request->validate([
        'annee_id' => 'required|exists:annees,id'
    ]);

    $inscription = Inscription::where('id', $inscriptionId)
        ->where('annee_id', $request->annee_id)
        ->first();

    if (!$inscription) {
        return response()->json([]);
    }

    $frais = InscriptionFrais::with('frais')
        ->where('inscription_id', $inscription->id)
        ->get();

    $data = $frais->map(function ($item) {

        $paye = Paiement::where('inscription_id', $item->inscription_id)
            ->where('frais_id', $item->frais_id)
            ->sum('montant_verse');

        $total = $item->montant_frais ?? $item->frais->montant;

        return [
            'frais_id' => $item->frais_id,
            'nom' => $item->frais->nom,
             'description' => $item->frais->description,
            'montant_frais' => $total,
            'montant_paye' => $paye,
            'reste' => $total - $paye,
            'statut' => ($total - $paye) <= 0 ? 'soldé' : 'non payé',
        ];
    });

    return response()->json($data);
}
    
public function getInscriptionsByClasse($classeId, Request $request)
{
    return Inscription::with('eleve')
        ->where('classe_id', $classeId)
        ->where('annee_id', $request->annee_id)
        ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
        ->orderBy('eleves.nom')
        ->orderBy('eleves.prenom')
        ->select('inscriptions.*')
        ->get();
}

     public function show($id)
    {
        $paiement = Paiement::with(['inscription.eleve'])->findOrFail($id);
        return view('paiements.show', compact('paiement'));
    }

    /**
     * Affiche le formulaire pour éditer un paiement.
     */
   public function edit($id)
{
    $paiement = Paiement::with(['inscription.eleve', 'inscription.annee', 'frais'])->findOrFail($id);
    // Récupérer toutes les classes (si nécessaire pour l'affichage ou l'édition)
    $classes = \App\Models\Classe::all();
    // Récupérer toutes les inscriptions (pour afficher les élèves)
    $inscriptions = \App\Models\Inscription::with('eleve')->get();
    // Récupérer tous les frais
    $frais = \App\Models\Frais::all();
    return view('paiements.edit', compact('paiement', 'classes', 'inscriptions', 'frais'));
}


    /**
     * Supprime un paiement spécifique.
     */
    public function destroy($id)
    {
        $paiement = Paiement::findOrFail($id);
        $paiement->delete();

        return redirect()->route('paiements.index')
                         ->with('success', 'Le paiement a été supprimé avec succès.');
    }



public function historique()
{
    // 🔹 Paiements par jour
    $paiementsParJour = Paiement::select(
            DB::raw('DATE(created_at) as jour'),
            DB::raw('SUM(montant_verse) as total')
        )
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('jour', 'desc')
        ->get();

    // 🔹 Paiements par mois
    $paiementsParMois = Paiement::select(
            DB::raw('EXTRACT(YEAR FROM created_at) as annee'),
            DB::raw('EXTRACT(MONTH FROM created_at) as mois'),
            DB::raw('SUM(montant_verse) as total')
        )
        ->groupBy(
            DB::raw('EXTRACT(YEAR FROM created_at)'),
            DB::raw('EXTRACT(MONTH FROM created_at)')
        )
        ->orderBy('annee', 'desc')
        ->orderBy('mois', 'desc')
        ->get();

    // 🔹 Paiements par année
    $paiementsParAnnee = Paiement::select(
            DB::raw('EXTRACT(YEAR FROM created_at) as annee'),
            DB::raw('SUM(montant_verse) as total')
        )
        ->groupBy(DB::raw('EXTRACT(YEAR FROM created_at)'))
        ->orderBy('annee', 'desc')
        ->get();

    return view('paiements.historique', compact(
        'paiementsParJour',
        'paiementsParMois',
        'paiementsParAnnee'
    ));
}

    public function exportPdf(Request $request)
{
    $query = Paiement::with([
        'inscription.eleve',
        'inscription.classe',
        'inscription.annee',
        'frais'
    ]);

    // 🎯 Filtres dynamiques (au choix)
    if ($request->filled('annee_id')) {
        $query->whereHas('inscription', function ($q) use ($request) {
            $q->where('annee_id', $request->annee_id);
        });
    }

    if ($request->filled('classe_id')) {
        $query->whereHas('inscription', function ($q) use ($request) {
            $q->where('classe_id', $request->classe_id);
        });
    }

    if ($request->filled('inscription_id')) {
        $query->where('inscription_id', $request->inscription_id);
    }

    if ($request->filled('frais_id')) {
        $query->where('frais_id', $request->frais_id);
    }

    if ($request->filled('date_debut')) {
        $query->whereDate('date_paiement', '>=', $request->date_debut);
    }

    if ($request->filled('date_fin')) {
        $query->whereDate('date_paiement', '<=', $request->date_fin);
    }

    $paiements = $query->orderBy('date_paiement')->get();

    $total = $paiements->sum('montant_verse');

    $pdf = Pdf::loadView('paiements.export-pdf', [
        'paiements' => $paiements,
        'total'     => $total,
        'filters'   => $request->all(),
    ])->setPaper('a4', 'landscape');

    return $pdf->download('liste_paiements.pdf');
}

public function storeUP(Request $request)
{
    
    $request->validate([
        'annee_id'       => 'required|exists:annees,id',
        'inscription_id' => 'required|exists:inscriptions,id',
        'frais_ids'      => 'required|array',
        'frais_ids.*'    => 'exists:frais,id',
        'montants'       => 'required|array',
        'montants.*'     => 'numeric|min:1',
        'mode_paiement'  => 'required|string',
        'date_paiement'  => 'required|date',
    ]);

    $inscription = Inscription::with(['eleve','classe','annee'])
        ->where('id', $request->inscription_id)
        ->where('annee_id', $request->annee_id)
        ->firstOrFail();

    DB::beginTransaction();

    try {

        $details = [];
        $total   = 0;

        // 🔢 Numéro unique pour tout le lot
        $numeroLot = 'REC-' . date('Y') . '-' . strtoupper(Str::random(6));

        foreach ($request->frais_ids as $index => $frais_id) {

            $montant_verse = (float) $request->montants[$index];

            $ligne = DB::table('inscription_frais')
                ->where('inscription_id', $inscription->id)
                ->where('frais_id', $frais_id)
                ->where('annee_id', $request->annee_id)
                ->lockForUpdate()
                ->first();

            if (!$ligne) {
                throw new \Exception("Frais non initialisé.");
            }

            if ($ligne->statut === 'soldé') {
                throw new \Exception("Un des frais sélectionnés est déjà soldé.");
            }

            $nouveauPaye = $ligne->montant_paye + $montant_verse;

            if ($nouveauPaye > $ligne->montant_frais) {
                throw new \Exception("Montant versé supérieur au reste.");
            }

            $reste = $ligne->montant_frais - $nouveauPaye;

            // 🎯 Statut
            if ($nouveauPaye == 0) {
                $statut = 'non_payé';
            } elseif ($nouveauPaye < $ligne->montant_frais) {
                $statut = 'partiellement_payé';
            } else {
                $statut = 'soldé';
            }

            // 💾 Création paiement
            $paiement = Paiement::create([
                'inscription_id' => $inscription->id,
                'frais_id'       => $frais_id,
                'montant_verse'  => $montant_verse,
                'montant_total'  => $ligne->montant_frais,
                'mode_paiement'  => $request->mode_paiement,
                'date_paiement'  => $request->date_paiement,
                'numero_recu'    => $numeroLot,
                'reference'      => 'REF-' . strtoupper(Str::random(8)),
            ]);

            // 🔁 Mise à jour pivot
            DB::table('inscription_frais')
                ->where('id', $ligne->id)
                ->update([
                    'montant_paye' => $nouveauPaye,
                    'reste'        => $reste,
                    'statut'       => $statut,
                    'updated_at'   => now(),
                ]);

            // 🧾 Recette
            Recette::create([
                'paiement_id'    => $paiement->id,
                'inscription_id' => $inscription->id,
                'montant_verse'  => $paiement->montant_verse,
                'date_paiement'  => $paiement->date_paiement,
                'mode_paiement'  => $paiement->mode_paiement,
                'numero_recu'    => $numeroLot,
            ]);

            // 📋 Détails pour le reçu
            $frais = Frais::find($frais_id);

            $details[] = [
                'nom'     => $frais->nom,
                'montant' => $montant_verse,
            ];

            $total += $montant_verse;
        }

        DB::commit();

        // 📜 Historique
        $historique = Paiement::where('inscription_id', $inscription->id)
            ->orderBy('date_paiement', 'desc')
            ->get();

   return response()->json([
    'success' => true,
    'message' => '✅ Paiement enregistré avec Succès !',
    'redirect' => route('paiements.recu', $numeroLot)
]);

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->withErrors(
            "❌ Erreur lors de l'enregistrement : " . $e->getMessage()
        );
    }
}
public function updateUP(Request $request, $id)
{
    $request->validate([
        'inscription_id' => 'required|exists:inscriptions,id',
        'annee_id'       => 'required|exists:annees,id',
        'frais_id'       => 'required|exists:frais,id',
        'montant_verse'  => 'required|numeric|min:1',
        'mode_paiement'  => 'required|string',
        'date_paiement'  => 'required|date',
    ]);

    $paiement = Paiement::findOrFail($id);

    DB::transaction(function () use ($request, $paiement) {

        $ligne = DB::table('inscription_frais')
            ->where('inscription_id', $request->inscription_id)
            ->where('frais_id', $request->frais_id)
            ->where('annee_id', $request->annee_id)
            ->lockForUpdate()
            ->first();

        if (!$ligne) {
            throw new \Exception("❌ Frais introuvable.");
        }

        // 🔄 recalcul propre
        $nouveauPaye = $ligne->montant_paye - $paiement->montant_verse + $request->montant_verse;

        if ($nouveauPaye < 0 || $nouveauPaye > $ligne->montant_frais) {
            throw new \Exception("❌ Montant invalide.");
        }

        $reste = $ligne->montant_frais - $nouveauPaye;

        if ($nouveauPaye == 0) {
            $statut = 'non_payé';
        } elseif ($nouveauPaye < $ligne->montant_frais) {
            $statut = 'partiellement_payé';
        } else {
            $statut = 'soldé';
        }

        $paiement->update([
            'montant_verse' => $request->montant_verse,
            'mode_paiement' => $request->mode_paiement,
            'date_paiement' => $request->date_paiement,
        ]);

        DB::table('inscription_frais')
            ->where('id', $ligne->id)
            ->update([
                'montant_paye' => $nouveauPaye,
                'reste'        => $reste,
                'statut'       => $statut,
                'updated_at'   => now(),
            ]);

        Recette::where('paiement_id', $paiement->id)?->update([
            'montant_verse' => $request->montant_verse,
            'date_paiement' => $request->date_paiement,
            'mode_paiement' => $request->mode_paiement,
        ]);
    });

    return back()->with('success', '✅ Paiement mis à jour avec succès.');
}


public function createUP()
{
    $annees = Annee::all();

    // Année en cours
    $anneeEnCours = Annee::where('en_cours', true)->first();

    if (!$anneeEnCours) {
        $anneeEnCours = $annees->firstWhere('en_cours', 't');
    }

    if (!$anneeEnCours) {
        $anneeEnCours = $annees->firstWhere('id', 2);
    }

    // Inscriptions triées par ordre alphabétique
   $inscriptions = Inscription::with([
        'eleve',
        'classe',
        'inscriptionFrais.frais'
    ])
    ->alphabetique()
    ->get();
    $classes = Classe::orderByNiveau()->get();

    $cycles = Cycle::all();

    return view('paiements.createUP', compact(
        'annees',
        'anneeEnCours',
        'inscriptions',
        'cycles',
        'classes'
    ));
}



public function recu($numeroLot)
{
    // 🔍 Récupération des paiements du reçu
    $paiements = Paiement::with([
    'inscription.eleve',
    'inscription.classe',
    'inscription.annee',
    'inscription.frais', // 🔥 AJOUT ICI
    'frais'
])->where('numero_recu', $numeroLot)->get();

    if ($paiements->isEmpty()) {
        abort(404, "Aucun paiement trouvé pour ce reçu.");
    }

    $paiement     = $paiements->first();
    $inscription  = $paiement->inscription;

    if (!$inscription) {
        abort(500, "Inscription non trouvée pour ce paiement.");
    }

    // 📋 Détails du reçu (CE REÇU uniquement)
    $details = $paiements->map(function ($p) {
        return [
            'nom'     => $p->frais->nom ?? $p->frais->description ?? 'Frais inconnu',
            'montant' => $p->montant_verse ?? 0,
        ];
    });

    // 💰 Total de CE reçu
    $total_ce_recu = $paiements->sum('montant_verse');

    // ✅ Total payé (TOUS les paiements de l'inscription)
    $total_paye = Paiement::where('inscription_id', $inscription->id)
        ->sum('montant_verse');

    // ✅ TOTAL RÉEL DES FRAIS (via pivot inscription_frais)
    $total_frais = $inscription->frais->sum('pivot.montant_frais');

    // ✅ RESTE RÉEL
    $reste = $inscription->frais->sum('pivot.reste');

    // 🎯 Statut réel
    if ($reste == 0) {
        $statut = 'Soldé';
    } elseif ($total_paye > 0) {
        $statut = 'Partiellement payé';
    } else {
        $statut = 'Non payé';
    }

// 📄 Génération PDF (format ticket pro)
$pdf = Pdf::loadView('paiements.recu_multiple', [
    'paiement'        => $paiement,
    'paiements'       => $paiements,
    'inscription'     => $inscription,
    'details'         => $details,
    'total_ce_recu'   => $total_ce_recu,
    'total_paye'      => $total_paye,
    'total_frais'     => $total_frais,
    'reste'           => $reste,
    'statut'          => $statut,
    'numeroLot'       => $numeroLot,
])

// ✅ FORMAT TICKET (très important)
->setPaper([0, 0, 226.77, 600], 'portrait') // 80mm thermal printer

// ⚡ options PRO (qualité + performance)
->setOptions([
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,
    'defaultFont' => 'Courier',
]);

// 📥 téléchargement
return $pdf->download("ticket-paiement-$numeroLot.pdf");
}


}
