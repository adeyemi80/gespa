<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Services\ThermalPrinterService;
use App\Services\RecuThermiqueService;
use App\Models\Frais;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\PaiementAchat;
use App\Models\Inscription;
use App\Models\InscriptionFrais;
use App\Models\Annee;
use App\Models\Recette;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{

public function index(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Paiements classiques
    |--------------------------------------------------------------------------
    */
    $paiements = Paiement::with([
        'inscription.eleve',
        'inscription.classe',
        'inscription.annee',
        'frais',
        'recette',
    ])
    ->when($request->filled('annee_id'), function ($query) use ($request) {
        $query->whereHas('inscription', function ($q) use ($request) {
            $q->where('annee_id', $request->annee_id);
        });
    })
    ->when($request->filled('search'), function ($query) use ($request) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where('numero_recu', 'ILIKE', "%{$search}%")

                ->orWhereHas('inscription.eleve', function ($q2) use ($search) {
                    $q2->where('nom', 'ILIKE', "%{$search}%")
                       ->orWhere('prenom', 'ILIKE', "%{$search}%")
                       ->orWhere('matricule', 'ILIKE', "%{$search}%");
                })

                ->orWhereHas('frais', function ($q2) use ($search) {
                    $q2->where('nom', 'ILIKE', "%{$search}%");
                });
        });
    })
    ->get();


    /*
    |--------------------------------------------------------------------------
    | Paiements provenant de paiement_achats
    |--------------------------------------------------------------------------
    */
    $paiementsAchats = PaiementAchat::with([
        'inscription.eleve',
        'inscription.classe',
        'inscription.annee',
        'frais',
        'recette',
    ])
    ->when($request->filled('annee_id'), function ($query) use ($request) {
        $query->where(function ($q) use ($request) {

            // Si l'inscription existe
            $q->whereHas('inscription', function ($q2) use ($request) {
                $q2->where('annee_id', $request->annee_id);
            })

            // Ou si le paiement possède une recette liée à l'année
            ->orWhereHas('recette', function ($q2) use ($request) {
                $q2->where('annee_id', $request->annee_id);
            });
        });
    })
    ->when($request->filled('search'), function ($query) use ($request) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            // Numéro de reçu
            $q->where('numero_recu', 'ILIKE', "%{$search}%")

                // Nom directement enregistré dans paiement_achats
                ->orWhere('nom', 'ILIKE', "%{$search}%")

                // Prénom directement enregistré dans paiement_achats
                ->orWhere('prenom', 'ILIKE', "%{$search}%")

                // Téléphone
                ->orWhere('telephone', 'ILIKE', "%{$search}%")

                // Élève lié à l'inscription
                ->orWhereHas('inscription.eleve', function ($q2) use ($search) {
                    $q2->where('nom', 'ILIKE', "%{$search}%")
                       ->orWhere('prenom', 'ILIKE', "%{$search}%")
                       ->orWhere('matricule', 'ILIKE', "%{$search}%");
                })

                // Frais
                ->orWhereHas('frais', function ($q2) use ($search) {
                    $q2->where('nom', 'ILIKE', "%{$search}%");
                });
        });
    })
    ->get();


    /*
    |--------------------------------------------------------------------------
    | Uniformisation des deux sources
    |--------------------------------------------------------------------------
    |
    | On ajoute :
    | - source
    | - montant_affiche
    |
    | afin que la vue puisse afficher les deux types de paiements
    | avec la même structure.
    |
    */
    $paiements = $paiements->map(function ($paiement) {

        $paiement->source = 'paiement';

        $paiement->montant_affiche = (float) $paiement->montant_verse;

        return $paiement;
    });


    $paiementsAchats = $paiementsAchats->map(function ($paiement) {

        $paiement->source = 'paiement_achat';

        $paiement->montant_affiche = (float) $paiement->montant;

        return $paiement;
    });


    /*
    |--------------------------------------------------------------------------
    | Fusion
    |--------------------------------------------------------------------------
    */
    $tousLesPaiements = $paiements
        ->merge($paiementsAchats)
        ->sort(function ($a, $b) {

            $dateA = $a->date_paiement
                ? \Carbon\Carbon::parse($a->date_paiement)->timestamp
                : 0;

            $dateB = $b->date_paiement
                ? \Carbon\Carbon::parse($b->date_paiement)->timestamp
                : 0;

            if ($dateA === $dateB) {
                return ($b->id ?? 0) <=> ($a->id ?? 0);
            }

            return $dateB <=> $dateA;
        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Pagination manuelle
    |--------------------------------------------------------------------------
    */
    $perPage = 50;

    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();

    $total = $tousLesPaiements->count();

    $items = $tousLesPaiements
        ->slice(($currentPage - 1) * $perPage, $perPage)
        ->values();


    $paiements = new \Illuminate\Pagination\LengthAwarePaginator(
        $items,
        $total,
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Années scolaires
    |--------------------------------------------------------------------------
    */
    $annees = Annee::orderByDesc('id')->get();


    /*
    |--------------------------------------------------------------------------
    | Vue
    |--------------------------------------------------------------------------
    */
    return view('paiements.index', compact(
        'paiements',
        'annees'
    ));
}


public function destroyAchat(string $numeroRecu)
{
    try {
        DB::transaction(function () use ($numeroRecu) {

            /*
             * On verrouille toutes les lignes du reçu.
             *
             * C'est important pour éviter qu'un autre paiement
             * ne modifie les mêmes données pendant la suppression.
             */
            $paiementsAchats = PaiementAchat::where(
                'numero_recu',
                $numeroRecu
            )
            ->lockForUpdate()
            ->get();

            if ($paiementsAchats->isEmpty()) {
                throw new \RuntimeException(
                    "Le reçu {$numeroRecu} est introuvable."
                );
            }

            foreach ($paiementsAchats as $paiementAchat) {

                /*
                 * Si le paiement est lié à une inscription,
                 * on restaure les montants dans inscription_frais.
                 */
                if (
                    $paiementAchat->inscription_id &&
                    $paiementAchat->frais_id
                ) {

                    $inscriptionFrais = DB::table('inscription_frais')
                        ->where(
                            'inscription_id',
                            $paiementAchat->inscription_id
                        )
                        ->where(
                            'frais_id',
                            $paiementAchat->frais_id
                        )
                        ->lockForUpdate()
                        ->first();

                    if ($inscriptionFrais) {

                        $montantPayeActuel = (float) $inscriptionFrais->montant_paye;
                        $montantFrais = (float) $inscriptionFrais->montant_frais;
                        $montantPaiement = (float) $paiementAchat->montant;

                        /*
                         * On retire exactement le montant
                         * correspondant à ce paiement.
                         */
                        $nouveauMontantPaye = max(
                            0,
                            $montantPayeActuel - $montantPaiement
                        );

                        /*
                         * Le reste ne peut jamais être négatif.
                         */
                        $nouveauReste = max(
                            0,
                            $montantFrais - $nouveauMontantPaye
                        );

                        /*
                         * Recalcul du statut avec les valeurs
                         * exactes de ta base.
                         */
                        if ($nouveauMontantPaye <= 0) {

                            $nouveauStatut = 'non_payé';

                        } elseif ($nouveauReste > 0) {

                            $nouveauStatut = 'partiellement_payé';

                        } else {

                            $nouveauStatut = 'soldé';
                        }

                        DB::table('inscription_frais')
                            ->where('id', $inscriptionFrais->id)
                            ->update([
                                'montant_paye' => $nouveauMontantPaye,
                                'reste' => $nouveauReste,
                                'statut' => $nouveauStatut,
                                'updated_at' => now(),
                            ]);
                    }
                }

                /*
                 * Suppression des recettes liées à CE paiement.
                 *
                 * On ne touche pas aux autres recettes.
                 */
                DB::table('recettes')
                    ->where(
                        'paiement_achat_id',
                        $paiementAchat->id
                    )
                    ->delete();
            }

            /*
             * Enfin, suppression de toutes les lignes
             * paiement_achats appartenant à ce reçu.
             */
            PaiementAchat::where(
                'numero_recu',
                $numeroRecu
            )->delete();
        });

        return redirect()
            ->route('paiements.index')
            ->with(
                'success',
                "Le reçu {$numeroRecu} et ses données associées ont été supprimés avec succès."
            );

    } catch (\Throwable $e) {

        Log::error(
            'Erreur lors de la suppression du reçu paiement achat',
            [
                'numero_recu' => $numeroRecu,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]
        );

        return redirect()
            ->back()
            ->with(
                'error',
                "Impossible de supprimer le reçu {$numeroRecu}. Aucune donnée n'a été modifiée."
            );
    }
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

   
public function paiement()
{
    return view('paiements.paiement');
}
public function achat()
{
    return view('paiements.achat');
}
public function fraisFilter()
{
    return view('paiements.fraisFilter');
}
public function filtre()
{
    return view('paiements.filtre');
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




}
