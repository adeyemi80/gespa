<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\EleveExterneTd;
use App\Models\Frais;
use App\Models\InscriptionTdExterne;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InscriptionTdExterneController extends Controller
{
    /**
     * Identifiant de la catégorie des travaux dirigés.
     */
    private const CATEGORIE_TD_ID = 17;

    /**
     * Construire la requête des frais TD applicables
     * à une année scolaire et à une classe.
     *
     * Le montant se trouve dans annee_classe_frais,
     * et non dans frais.
     */
    private function requeteFraisApplicables(
        int $anneeId,
        int $classeId
    ) {
        return DB::table('annee_classe_frais as acf')
            ->join('frais as f', 'f.id', '=', 'acf.frais_id')
            ->where('acf.annee_id', $anneeId)
            ->where('acf.classe_id', $classeId)
            ->where(
                'f.categorie_recette_id',
                self::CATEGORIE_TD_ID
            )
            ->select([
                'f.id',
                'f.nom',
                'f.description',
                'f.categorie_recette_id',
                'acf.annee_id',
                'acf.classe_id',
                'acf.montant',
            ]);
    }

    /**
     * Vérifier et récupérer un frais TD applicable
     * à une année et à une classe.
     */
    private function obtenirFraisApplicable(
        int $fraisId,
        int $anneeId,
        int $classeId
    ): object {
        $frais = $this->requeteFraisApplicables(
            $anneeId,
            $classeId
        )
            ->where('f.id', $fraisId)
            ->first();

        if (!$frais) {
            throw ValidationException::withMessages([
                'frais_id' =>
                    'Le frais TD sélectionné n’est pas applicable à cette année scolaire et à cette classe.',
            ]);
        }

        if (
            $frais->montant === null
            || !is_numeric($frais->montant)
            || (float) $frais->montant < 0
        ) {
            throw ValidationException::withMessages([
                'frais_id' =>
                    'Le montant du frais TD sélectionné est invalide ou n’est pas renseigné.',
            ]);
        }

        return $frais;
    }

    /**
     * Afficher la liste des inscriptions TD externes.
     */
    public function index(Request $request)
    {
        $query = InscriptionTdExterne::query()
            ->with([
                'eleve',
                'annee',
                'classe',
                'frais',
            ]);

        /*
         * Filtre par année scolaire.
         */
        if ($request->filled('annee_id')) {
            $query->where(
                'annee_id',
                $request->input('annee_id')
            );
        }

        /*
         * Filtre par classe.
         */
        if ($request->filled('classe_id')) {
            $query->where(
                'classe_id',
                $request->input('classe_id')
            );
        }

        /*
         * Filtre par statut.
         */
        if ($request->filled('statut')) {
            $query->where(
                'statut',
                $request->input('statut')
            );
        }

        /*
         * Recherche par matricule, nom ou prénom.
         */
        if ($request->filled('recherche')) {
            $recherche = trim($request->input('recherche'));

            $query->whereHas('eleve', function ($q) use ($recherche) {
                $q->where('matricule', 'ILIKE', "%{$recherche}%")
                    ->orWhere('nom', 'ILIKE', "%{$recherche}%")
                    ->orWhere('prenom', 'ILIKE', "%{$recherche}%");
            });
        }

        $inscriptions = $query
            ->orderByDesc('date_inscription')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $classes = Classe::query()
            ->orderBy('nom')
            ->get();

        return view(
            'inscriptions_td_externes.index',
            compact(
                'inscriptions',
                'annees',
                'classes'
            )
        );
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $eleves = EleveExterneTd::query()
            ->where('actif', true)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $classes = Classe::query()
            ->orderBy('nom')
            ->get();

        return view(
            'inscriptions_td_externes.create',
            compact(
                'eleves',
                'annees',
                'classes'
            )
        );
    }

    /**
     * Enregistrer une inscription TD externe.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'eleve_externe_td_id' => [
                'required',
                'integer',
                'exists:eleves_externes_td,id',
            ],

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

            'frais_id' => [
                'required',
                'integer',
                'exists:frais,id',
            ],

            'date_inscription' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'active',
                    'suspendue',
                    'terminee',
                ]),
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $inscription = DB::transaction(function () use ($validated) {

                /*
                 * 1. Vérifier que l'élève existe et qu'il est actif.
                 */
                $eleve = EleveExterneTd::query()
                    ->whereKey($validated['eleve_externe_td_id'])
                    ->where('actif', true)
                    ->first();

                if (!$eleve) {
                    throw ValidationException::withMessages([
                        'eleve_externe_td_id' =>
                            'L’élève externe sélectionné n’existe pas ou est désactivé.',
                    ]);
                }

                /*
                 * 2. Empêcher une deuxième inscription
                 * du même élève pour la même année.
                 */
                $existe = InscriptionTdExterne::query()
                    ->where(
                        'eleve_externe_td_id',
                        $validated['eleve_externe_td_id']
                    )
                    ->where(
                        'annee_id',
                        $validated['annee_id']
                    )
                    ->exists();

                if ($existe) {
                    throw ValidationException::withMessages([
                        'eleve_externe_td_id' =>
                            'Cet élève possède déjà une inscription TD pour cette année scolaire.',
                    ]);
                }

                /*
                 * 3. Vérifier le frais et récupérer son montant.
                 *
                 * Le montant provient de annee_classe_frais.
                 * Aucun montant envoyé par le formulaire n'est utilisé.
                 */
                $frais = $this->obtenirFraisApplicable(
                    (int) $validated['frais_id'],
                    (int) $validated['annee_id'],
                    (int) $validated['classe_id']
                );

                /*
                 * 4. Créer l'inscription.
                 */
                return InscriptionTdExterne::create([
                    'eleve_externe_td_id' =>
                        $validated['eleve_externe_td_id'],

                    'annee_id' =>
                        $validated['annee_id'],

                    'classe_id' =>
                        $validated['classe_id'],

                    'frais_id' =>
                        $frais->id,

                    'date_inscription' =>
                        $validated['date_inscription'],

                    'montant_total' =>
                        (float) $frais->montant,

                    'statut' =>
                        $validated['statut'],

                    'observations' =>
                        $validated['observations'] ?? null,
                ]);
            });

            return redirect()
                ->route(
                    'inscriptions-td-externes.show',
                    $inscription->id
                )
                ->with(
                    'success',
                    'Inscription TD externe enregistrée avec succès.'
                );

        } catch (ValidationException $e) {
            /*
             * Conserver les erreurs de validation des champs.
             */
            throw $e;

        } catch (QueryException $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'erreur' =>
                        'Impossible d’enregistrer cette inscription. Vérifiez les données et les contraintes de la base.',
                ]);
        }
    }

    /**
     * Afficher le détail d'une inscription.
     */
    public function show(
        InscriptionTdExterne $inscriptions_td_externe
    ) {
        $inscription = $inscriptions_td_externe->load([
            'eleve',
            'annee',
            'classe',
            'frais',
        ]);

        /*
         * Récupérer les paiements de cette inscription TD externe.
         */
        $paiements = DB::table('paiement_achats')
            ->where(
                'inscription_td_externe_id',
                $inscription->id
            )
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->get();

        /*
         * IMPORTANT :
         * La colonne réelle est montant, et non montant_verse.
         */
        $totalPaye = (float) DB::table('paiement_achats')
            ->where(
                'inscription_td_externe_id',
                $inscription->id
            )
            ->sum('montant');

        /*
         * Calcul du reste à payer.
         */
        $montantTotal = (float) $inscription->montant_total;

        $resteAPayer = max(
            0,
            $montantTotal - $totalPaye
        );

        /*
         * Taux de paiement en pourcentage.
         */
        $tauxPaiement = $montantTotal > 0
            ? round(($totalPaye / $montantTotal) * 100, 2)
            : 0;

        return view(
            'inscriptions_td_externes.show',
            compact(
                'inscription',
                'paiements',
                'totalPaye',
                'montantTotal',
                'resteAPayer',
                'tauxPaiement'
            )
        );
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(
        InscriptionTdExterne $inscriptions_td_externe
    ) {
        $inscription = $inscriptions_td_externe->load([
            'eleve',
            'annee',
            'classe',
            'frais',
        ]);

        $eleves = EleveExterneTd::query()
            ->where(function ($query) use ($inscription) {
                $query->where('actif', true)
                    ->orWhere(
                        'id',
                        $inscription->eleve_externe_td_id
                    );
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $classes = Classe::query()
            ->orderBy('nom')
            ->get();

        /*
         * Les frais disponibles sont récupérés depuis
         * annee_classe_frais pour l'année et la classe actuelles.
         */
        $fraisDisponibles = $this->requeteFraisApplicables(
            (int) $inscription->annee_id,
            (int) $inscription->classe_id
        )
            ->orderBy('f.nom')
            ->get();

        return view(
            'inscriptions_td_externes.edit',
            compact(
                'inscription',
                'eleves',
                'annees',
                'classes',
                'fraisDisponibles'
            )
        );
    }

    /**
     * Mettre à jour une inscription TD externe.
     */
    public function update(
        Request $request,
        InscriptionTdExterne $inscriptions_td_externe
    ) {
        $validated = $request->validate([
            'eleve_externe_td_id' => [
                'required',
                'integer',
                'exists:eleves_externes_td,id',
            ],

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

            'frais_id' => [
                'required',
                'integer',
                'exists:frais,id',
            ],

            'date_inscription' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'active',
                    'suspendue',
                    'terminee',
                ]),
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            DB::transaction(function () use (
                $validated,
                $inscriptions_td_externe
            ) {
                /*
                 * Verrouiller l'inscription pendant la modification.
                 */
                $inscription = InscriptionTdExterne::query()
                    ->whereKey($inscriptions_td_externe->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * Empêcher les doublons pour une même année.
                 */
                $doublon = InscriptionTdExterne::query()
                    ->where(
                        'eleve_externe_td_id',
                        $validated['eleve_externe_td_id']
                    )
                    ->where(
                        'annee_id',
                        $validated['annee_id']
                    )
                    ->where('id', '<>', $inscription->id)
                    ->exists();

                if ($doublon) {
                    throw ValidationException::withMessages([
                        'eleve_externe_td_id' =>
                            'Cet élève possède déjà une inscription pour cette année.',
                    ]);
                }

                /*
                 * Vérifier si des paiements existent déjà.
                 */
                $aDesPaiements = DB::table('paiement_achats')
                    ->where(
                        'inscription_td_externe_id',
                        $inscription->id
                    )
                    ->exists();

                /*
                 * Si des paiements existent, interdire le changement
                 * d'élève, d'année, de classe ou de frais.
                 */
                if ($aDesPaiements) {
                    $changementInterdit =
                        (int) $validated['eleve_externe_td_id']
                            !== (int) $inscription->eleve_externe_td_id

                        || (int) $validated['annee_id']
                            !== (int) $inscription->annee_id

                        || (int) $validated['classe_id']
                            !== (int) $inscription->classe_id

                        || (int) $validated['frais_id']
                            !== (int) $inscription->frais_id;

                    if ($changementInterdit) {
                        throw ValidationException::withMessages([
                            'frais_id' =>
                                'Cette inscription possède déjà des paiements. Le changement d’élève, d’année, de classe ou de frais est interdit.',
                        ]);
                    }
                }

                /*
                 * Vérifier le frais applicable et récupérer
                 * le montant réel dans annee_classe_frais.
                 */
                $frais = $this->obtenirFraisApplicable(
                    (int) $validated['frais_id'],
                    (int) $validated['annee_id'],
                    (int) $validated['classe_id']
                );

                /*
                 * Mettre à jour l'inscription.
                 */
                $inscription->update([
                    'eleve_externe_td_id' =>
                        $validated['eleve_externe_td_id'],

                    'annee_id' =>
                        $validated['annee_id'],

                    'classe_id' =>
                        $validated['classe_id'],

                    'frais_id' =>
                        $frais->id,

                    'date_inscription' =>
                        $validated['date_inscription'],

                    'montant_total' =>
                        (float) $frais->montant,

                    'statut' =>
                        $validated['statut'],

                    'observations' =>
                        $validated['observations'] ?? null,
                ]);
            });

            return redirect()
                ->route(
                    'inscriptions-td-externes.show',
                    $inscriptions_td_externe->id
                )
                ->with(
                    'success',
                    'Inscription TD externe mise à jour avec succès.'
                );

        } catch (ValidationException $e) {
            throw $e;

        } catch (QueryException $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'erreur' =>
                        'Impossible de modifier cette inscription.',
                ]);
        }
    }

    /**
     * Supprimer une inscription.
     *
     * Une inscription qui possède des paiements ne peut pas
     * être supprimée, afin de préserver l'historique financier.
     */
    public function destroy(
        InscriptionTdExterne $inscriptions_td_externe
    ) {
        $aDesPaiements = DB::table('paiement_achats')
            ->where(
                'inscription_td_externe_id',
                $inscriptions_td_externe->id
            )
            ->exists();

        if ($aDesPaiements) {
            return back()->withErrors([
                'erreur' =>
                    'Cette inscription possède déjà des paiements. Elle ne peut pas être supprimée.',
            ]);
        }

        try {
            $inscriptions_td_externe->delete();

            return redirect()
                ->route('inscriptions-td-externes.index')
                ->with(
                    'success',
                    'Inscription TD externe supprimée avec succès.'
                );

        } catch (QueryException $e) {
            report($e);

            return back()->withErrors([
                'erreur' =>
                    'La suppression est impossible en raison de données associées à cette inscription.',
            ]);
        }
    }

    /**
     * Retourner les frais TD applicables à une année et une classe.
     *
     * Cette méthode peut être appelée par AJAX.
     */
    public function fraisDisponibles(Request $request)
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
        ]);

        /*
         * Vérifier que la classe existe.
         */
        Classe::query()
            ->whereKey($validated['classe_id'])
            ->firstOrFail();

        /*
         * Vérifier les frais TD affectés à cette année et cette classe.
         */
        $frais = $this->requeteFraisApplicables(
            (int) $validated['annee_id'],
            (int) $validated['classe_id']
        )
            ->orderBy('f.nom')
            ->get()
            ->map(function ($frais) {
                return [
                    'id' => $frais->id,
                    'nom' => $frais->nom,
                    'libelle' => $frais->nom,
                    'description' => $frais->description,
                    'montant' => $frais->montant !== null
                        ? (float) $frais->montant
                        : null,
                ];
            });

        return response()->json([
            'frais' => $frais,
        ]);
    }
}