<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\EleveExterneTd;
use App\Models\Frais;
use App\Models\InscriptionTdExterne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EleveExterneTdController extends Controller
{
    /**
     * Identifiant de la catégorie des frais de travaux dirigés.
     */
    private const CATEGORIE_TD_ID = 17;

    /**
     * Afficher la liste des élèves externes TD.
     *
     * Les filtres année, cycle et classe passent par les inscriptions.
     * Aucun de ces champs n'est recherché dans eleves_externes_td.
     */
    public function index(Request $request)
    {
        $query = EleveExterneTd::query()
            ->with([
                'inscriptions' => function ($query) {
                    $query->orderByDesc('date_inscription')
                        ->orderByDesc('id')
                        ->with([
                            'annee',
                            'classe.cycle',
                            'frais',
                        ]);
                },
            ]);

        /*
         * Recherche par matricule, nom, prénom ou téléphone.
         */
        if ($request->filled('recherche')) {
            $recherche = trim($request->input('recherche'));

            $query->where(function ($q) use ($recherche) {
                $q->where('matricule', 'ILIKE', "%{$recherche}%")
                    ->orWhere('nom', 'ILIKE', "%{$recherche}%")
                    ->orWhere('prenom', 'ILIKE', "%{$recherche}%")
                    ->orWhere('telephone', 'ILIKE', "%{$recherche}%")
                    ->orWhere(
                        'telephone_parent',
                        'ILIKE',
                        "%{$recherche}%"
                    );
            });
        }

        /*
         * Filtre par sexe.
         */
        if ($request->filled('sexe')) {
            $query->where('sexe', $request->input('sexe'));
        }

        /*
         * Filtre par statut actif/inactif.
         */
        if ($request->has('actif') && $request->input('actif') !== '') {
            $query->where(
                'actif',
                $request->boolean('actif')
            );
        }

        /*
         * Filtre par année scolaire à travers les inscriptions TD.
         */
        if ($request->filled('annee_id')) {
            $anneeId = $request->integer('annee_id');

            $query->whereHas('inscriptions', function ($q) use ($anneeId) {
                $q->where('annee_id', $anneeId);
            });
        }

        /*
         * Filtre par cycle à travers la classe de l'inscription.
         */
        if ($request->filled('cycle_id')) {
            $cycleId = $request->integer('cycle_id');

            $query->whereHas('inscriptions.classe', function ($q) use ($cycleId) {
                $q->where('cycle_id', $cycleId);
            });
        }

        /*
         * Filtre par classe à travers les inscriptions TD.
         */
        if ($request->filled('classe_id')) {
            $classeId = $request->integer('classe_id');

            $query->whereHas('inscriptions', function ($q) use ($classeId) {
                $q->where('classe_id', $classeId);
            });
        }

        $eleves = $query
            ->orderBy('nom')
            ->orderBy('prenom')
            ->paginate(15)
            ->withQueryString();

        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $cycles = Cycle::query()
            ->orderBy('nom')
            ->get();

        $classes = Classe::query()
            ->with('cycle')
            ->orderBy('nom')
            ->get();

        return view('eleves_externes_td.index', compact(
            'eleves',
            'annees',
            'cycles',
            'classes'
        ));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $cycles = Cycle::query()
            ->orderBy('nom')
            ->get();

        $classes = Classe::query()
            ->with('cycle')
            ->orderBy('nom')
            ->get();

        /*
         * Les frais sont chargés pour la catégorie TD.
         *
         * Leur montant applicable à l'année et à la classe
         * sera récupéré dans annee_classe_frais via AJAX.
         */
        $frais = $this->requeteFraisTd()
            ->orderBy('nom')
            ->get();

        return view('eleves_externes_td.create', compact(
            'annees',
            'cycles',
            'classes',
            'frais'
        ));
    }

    /**
     * Retourner la requête des frais de la catégorie TD.
     *
     * La table frais ne contient ni annee_id, ni classe_id,
     * ni montant.
     */
    private function requeteFraisTd(): Builder
    {
        return Frais::query()
            ->where(
                'categorie_recette_id',
                self::CATEGORIE_TD_ID
            );
    }

    /**
     * Retourner les frais TD applicables à une année et une classe.
     *
     * Le montant provient de annee_classe_frais.
     */
    public function fraisDisponibles(Request $request)
    {
        $validated = $request->validate([
            'annee_id' => [
                'required',
                'integer',
                'exists:annees,id',
            ],

            'cycle_id' => [
                'required',
                'integer',
                'exists:cycles,id',
            ],

            'classe_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],
        ]);

        /*
         * Vérifier que la classe appartient au cycle choisi.
         */
        $classe = Classe::query()
            ->whereKey($validated['classe_id'])
            ->where('cycle_id', $validated['cycle_id'])
            ->first();

        if (!$classe) {
            return response()->json([
                'message' => 'La classe sélectionnée ne correspond pas au cycle choisi.',
                'frais' => [],
            ], 422);
        }

        /*
         * Chercher les frais TD affectés à cette année et cette classe.
         *
         * Le montant est enregistré dans annee_classe_frais.montant.
         */
        $frais = DB::table('annee_classe_frais as acf')
            ->join('frais as f', 'f.id', '=', 'acf.frais_id')
            ->where('acf.annee_id', $validated['annee_id'])
            ->where('acf.classe_id', $validated['classe_id'])
            ->where(
                'f.categorie_recette_id',
                self::CATEGORIE_TD_ID
            )
            ->select([
                'f.id',
                'f.nom',
                'f.description',
                'acf.montant',
            ])
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

    /**
     * Enregistrer un élève externe et créer son inscription TD.
     *
     * L'élève et son inscription sont créés dans une transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            /*
             * Informations personnelles de l'élève.
             */
            'matricule' => [
                'nullable',
                'string',
                'max:255',
                'unique:eleves_externes_td,matricule',
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'prenom' => [
                'required',
                'string',
                'max:255',
            ],

            'sexe' => [
                'nullable',
                Rule::in([
                    'Masculin',
                    'Féminin',
                    'M',
                    'F',
                ]),
            ],

            'date_naissance' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone_parent' => [
                'nullable',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],

            /*
             * Informations de l'inscription TD.
             *
             * Ces données seront enregistrées dans
             * inscriptions_td_externes, et non dans l'élève.
             */
            'annee_id' => [
                'required',
                'integer',
                'exists:annees,id',
            ],

            'cycle_id' => [
                'required',
                'integer',
                'exists:cycles,id',
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
        ]);

        try {
            $inscription = DB::transaction(function () use ($validated) {

                /*
                 * 1. Vérifier la classe et son cycle.
                 */
                $classe = Classe::query()
                    ->whereKey($validated['classe_id'])
                    ->where('cycle_id', $validated['cycle_id'])
                    ->first();

                if (!$classe) {
                    throw ValidationException::withMessages([
                        'classe_id' => 'La classe sélectionnée ne correspond pas au cycle choisi.',
                    ]);
                }

                /*
                 * 2. Vérifier l'année scolaire.
                 */
                $anneeExiste = Annee::query()
                    ->whereKey($validated['annee_id'])
                    ->exists();

                if (!$anneeExiste) {
                    throw ValidationException::withMessages([
                        'annee_id' => 'L’année scolaire sélectionnée est invalide.',
                    ]);
                }

                /*
                 * 3. Vérifier que le frais est bien affecté
                 *    à cette année et à cette classe.
                 *
                 * IMPORTANT :
                 * annee_id, classe_id et montant sont recherchés
                 * dans annee_classe_frais, pas dans frais.
                 */
                $frais = DB::table('annee_classe_frais as acf')
                    ->join('frais as f', 'f.id', '=', 'acf.frais_id')
                    ->where('acf.annee_id', $validated['annee_id'])
                    ->where('acf.classe_id', $validated['classe_id'])
                    ->where('acf.frais_id', $validated['frais_id'])
                    ->where(
                        'f.categorie_recette_id',
                        self::CATEGORIE_TD_ID
                    )
                    ->select([
                        'f.id',
                        'f.nom',
                        'acf.montant',
                    ])
                    ->first();

                if (!$frais) {
                    throw ValidationException::withMessages([
                        'frais_id' => 'Le frais TD sélectionné n’est pas applicable à cette année scolaire et à cette classe.',
                    ]);
                }

                /*
                 * 4. Vérifier le montant affecté au frais.
                 *
                 * Le montant de l'inscription doit correspondre
                 * au montant défini dans annee_classe_frais.
                 */
                if ($frais->montant === null || !is_numeric($frais->montant)) {
                    throw ValidationException::withMessages([
                        'frais_id' => 'Aucun montant valide n’est défini pour ce frais TD dans cette classe et cette année scolaire.',
                    ]);
                }

                $montantTotal = (float) $frais->montant;

                if ($montantTotal < 0) {
                    throw ValidationException::withMessages([
                        'frais_id' => 'Le montant du frais TD ne peut pas être négatif.',
                    ]);
                }

                /*
                 * 5. Générer un matricule si nécessaire.
                 *
                 * Le matricule reste stocké uniquement dans
                 * eleves_externes_td.
                 */
                $matricule = $validated['matricule'] ?? null;

                if (blank($matricule)) {
                    do {
                        $matricule = 'TD-'
                            . now()->format('Y')
                            . '-'
                            . strtoupper(Str::random(8));
                    } while (
                        EleveExterneTd::query()
                            ->where('matricule', $matricule)
                            ->exists()
                    );
                }

                /*
                 * 6. Créer l'élève externe.
                 *
                 * On ne renseigne PAS :
                 * - annee_id
                 * - cycle_id
                 * - classe_id
                 *
                 * Ces colonnes n'existent pas dans
                 * eleves_externes_td.
                 */
                $eleve = EleveExterneTd::create([
                    'matricule' => $matricule,
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'sexe' => $validated['sexe'] ?? null,
                    'date_naissance' => $validated['date_naissance'] ?? null,
                    'telephone' => $validated['telephone'] ?? null,
                    'telephone_parent' => $validated['telephone_parent'] ?? null,
                    'adresse' => $validated['adresse'] ?? null,
                    'observations' => $validated['observations'] ?? null,
                    'actif' => array_key_exists('actif', $validated)
                        ? (bool) $validated['actif']
                        : true,
                ]);

                /*
                 * 7. Créer l'inscription TD.
                 *
                 * L'année, la classe, le frais et le montant
                 * sont enregistrés dans inscriptions_td_externes.
                 */
                $inscription = InscriptionTdExterne::create([
                    'eleve_externe_td_id' => $eleve->id,
                    'annee_id' => $validated['annee_id'],
                    'classe_id' => $validated['classe_id'],
                    'frais_id' => $frais->id,
                    'date_inscription' => $validated['date_inscription'],
                    'montant_total' => $montantTotal,
                    'statut' => 'active',
                    'observations' => null,
                ]);

                return $inscription;
            });

            return redirect()
                ->route('eleves-externes-td.show', [
                    'eleves_externes_td' => $inscription->eleve_externe_td_id,
                ])
                ->with(
                    'success',
                    'L’élève externe et son inscription TD ont été enregistrés avec succès.'
                );

        } catch (ValidationException $e) {
            /*
             * Laisser Laravel afficher les erreurs de validation
             * sur les champs concernés.
             */
            throw $e;

        } catch (QueryException $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'erreur' => 'Impossible d’enregistrer l’élève et son inscription TD. Vérifiez les données et les contraintes de la base de données.',
                ]);

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'erreur' => 'Une erreur est survenue lors de la création de l’élève et de son inscription TD.',
                ]);
        }
    }

    /**
     * Afficher la fiche d'un élève externe.
     *
     * Les informations scolaires sont récupérées depuis
     * ses inscriptions TD.
     */
    public function show(EleveExterneTd $eleves_externes_td)
    {
        $eleve = $eleves_externes_td->load([
            'inscriptions' => function ($query) {
                $query->orderByDesc('date_inscription')
                    ->orderByDesc('id')
                    ->with([
                        'annee',
                        'classe.cycle',
                        'frais',
                        'paiements',
                    ]);
            },
        ]);

        return view('eleves_externes_td.show', compact('eleve'));
    }

    /**
     * Afficher le formulaire de modification.
     *
     * Seules les informations personnelles de l'élève
     * sont modifiées ici.
     */
    public function edit(EleveExterneTd $eleves_externes_td)
    {
        $eleve = $eleves_externes_td;

        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $cycles = Cycle::query()
            ->orderBy('nom')
            ->get();

        $classes = Classe::query()
            ->with('cycle')
            ->orderBy('nom')
            ->get();

        return view('eleves_externes_td.edit', compact(
            'eleve',
            'annees',
            'cycles',
            'classes'
        ));
    }

    /**
     * Mettre à jour les informations personnelles.
     *
     * Les inscriptions et leur historique ne sont pas modifiés.
     */
    public function update(
        Request $request,
        EleveExterneTd $eleves_externes_td
    ) {
        $validated = $request->validate([
            'matricule' => [
                'required',
                'string',
                'max:255',
                Rule::unique('eleves_externes_td', 'matricule')
                    ->ignore($eleves_externes_td->id),
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'prenom' => [
                'required',
                'string',
                'max:255',
            ],

            'sexe' => [
                'nullable',
                Rule::in([
                    'Masculin',
                    'Féminin',
                    'M',
                    'F',
                ]),
            ],

            'date_naissance' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone_parent' => [
                'nullable',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'actif' => [
                'sometimes',
                'boolean',
            ],
        ]);

        try {
            $eleves_externes_td->update($validated);

            return redirect()
                ->route('eleves-externes-td.show', [
                    'eleves_externes_td' => $eleves_externes_td->id,
                ])
                ->with(
                    'success',
                    'Les informations de l’élève externe ont été mises à jour.'
                );

        } catch (QueryException $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'erreur' => 'Impossible de mettre à jour cet élève externe.',
                ]);
        }
    }

    /**
     * Supprimer un élève externe.
     *
     * Un élève ayant des inscriptions doit être désactivé
     * plutôt que supprimé, afin de conserver l'historique.
     */
    public function destroy(EleveExterneTd $eleves_externes_td)
    {
        try {
            if ($eleves_externes_td->inscriptions()->exists()) {
                return back()->withErrors([
                    'erreur' => 'Cet élève possède des inscriptions TD. Désactivez-le plutôt que de le supprimer.',
                ]);
            }

            $eleves_externes_td->delete();

            return redirect()
                ->route('eleves-externes-td.index')
                ->with(
                    'success',
                    'L’élève externe a été supprimé avec succès.'
                );

        } catch (QueryException $e) {
            report($e);

            return back()->withErrors([
                'erreur' => 'La suppression est impossible, car cet élève est référencé par d’autres données.',
            ]);
        }
    }

    /**
     * Activer ou désactiver un élève externe.
     */
    public function changerStatut(
        EleveExterneTd $eleves_externes_td
    ) {
        try {
            $nouvelEtat = !$eleves_externes_td->actif;

            $eleves_externes_td->update([
                'actif' => $nouvelEtat,
            ]);

            return back()->with(
                'success',
                $nouvelEtat
                    ? 'L’élève externe a été activé.'
                    : 'L’élève externe a été désactivé.'
            );

        } catch (QueryException $e) {
            report($e);

            return back()->withErrors([
                'erreur' => 'Impossible de modifier le statut de cet élève.',
            ]);
        }
    }
}