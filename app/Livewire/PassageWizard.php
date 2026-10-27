<?php

namespace App\Livewire;

use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Inscription;
use App\Models\Moyenne;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PassageWizard extends Component
{
    /*
    |--------------------------------------------------------------------------
    | ÉTAPES
    |--------------------------------------------------------------------------
    */

    public $step = 1;

    /*
    |--------------------------------------------------------------------------
    | PARAMÈTRES
    |--------------------------------------------------------------------------
    */

    public $cycle_id;
    public $annee_source_id;
    public $annee_accueil_id;

    public $classe_id;
    public $classe_accueil_id;

    /*
    |--------------------------------------------------------------------------
    | DONNÉES
    |--------------------------------------------------------------------------
    */

    public $classes = [];
    public $classes_accueil = [];

    /*
     * IMPORTANT :
     * $eleves reste une Collection car le Blade utilise :
     * $eleves->count()
     * $eleves->whereIn()
     * @forelse($eleves as ...)
     */
    public $eleves;

    /*
     * Toutes les données calculées concernant les élèves.
     *
     * NE PAS utiliser $eleves_data.
     * Toute la composante utilise désormais uniquement $elevesData.
     */
    public array $elevesData = [];

    public $selectAll = false;
    public array $selected_eleves = [];

    /*
    |--------------------------------------------------------------------------
    | HISTORIQUE
    |--------------------------------------------------------------------------
    */

    public $historique_passages = [];

    /*
    |--------------------------------------------------------------------------
    | MESSAGES
    |--------------------------------------------------------------------------
    */

    public $successMessage = '';
    public $errorMessage = '';
    public $warningMessage = '';

    /*
    |--------------------------------------------------------------------------
    | PARAMÈTRES DE PASSAGE
    |--------------------------------------------------------------------------
    */

    public $seuil_passage = 10;

    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->eleves = collect();

        /*
         * Année source :
         * année active/courante si possible.
         */
        $anneeCourante = Annee::query()
            ->orderByDesc('id')
            ->first();

        if ($anneeCourante) {
            $this->annee_source_id = $anneeCourante->id;

            /*
             * On essaie automatiquement de prendre
             * l'année suivante.
             */
            $this->annee_accueil_id = Annee::query()
                ->where('id', '>', $anneeCourante->id)
                ->orderBy('id')
                ->value('id');
        }

        $this->loadHistorique();
    }

    /*
    |--------------------------------------------------------------------------
    | CYCLE
    |--------------------------------------------------------------------------
    */

    public function updatedCycleId()
    {
        $this->classe_id = null;
        $this->classe_accueil_id = null;

        $this->classes = [];
        $this->classes_accueil = [];

        $this->eleves = collect();
        $this->elevesData = [];

        $this->selected_eleves = [];
        $this->selectAll = false;

        if (!$this->cycle_id || !$this->annee_source_id) {
            return;
        }

        $this->loadClasses();
    }

    /*
    |--------------------------------------------------------------------------
    | ANNÉE SOURCE
    |--------------------------------------------------------------------------
    */

    public function updatedAnneeSourceId()
    {
        $this->classe_id = null;
        $this->classe_accueil_id = null;

        $this->classes = [];
        $this->classes_accueil = [];

        $this->eleves = collect();
        $this->elevesData = [];

        $this->selected_eleves = [];
        $this->selectAll = false;

        if (!$this->annee_source_id) {
            return;
        }

        $this->loadClasses();

        /*
         * Si aucune année d'accueil n'est définie,
         * on cherche automatiquement la suivante.
         */
        if (!$this->annee_accueil_id) {
            $this->annee_accueil_id = Annee::query()
                ->where('id', '>', $this->annee_source_id)
                ->orderBy('id')
                ->value('id');
        }

        if ($this->annee_accueil_id) {
            $this->loadClassesAccueil();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ANNÉE D'ACCUEIL
    |--------------------------------------------------------------------------
    */

    public function updatedAnneeAccueilId()
    {
        $this->classe_accueil_id = null;

        $this->classes_accueil = [];

        if (!$this->annee_accueil_id) {
            return;
        }

        $this->loadClassesAccueil();

        /*
         * Si les élèves sont déjà chargés,
         * on recalcule seulement les destinations.
         */
        if ($this->eleves && $this->eleves->count() > 0) {
            $this->mettreAJourDestinations();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHARGER LES CLASSES SOURCE
    |--------------------------------------------------------------------------
    */

    public function loadClasses()
    {
        if (!$this->annee_source_id) {
            $this->classes = [];

            return;
        }

        $query = Classe::query()
            ->whereHas('annees', function ($q) {
                $q->where('annees.id', $this->annee_source_id);
            })
            ->orderBy('rang')
            ->orderBy('nom');

        if ($this->cycle_id) {
            $query->where('cycle_id', $this->cycle_id);
        }

        $this->classes = $query->get();
    }

    /*
    |--------------------------------------------------------------------------
    | CLASSE SOURCE
    |--------------------------------------------------------------------------
    */

    public function updatedClasseId()
    {
        $this->classe_accueil_id = null;

        $this->eleves = collect();
        $this->elevesData = [];

        $this->selected_eleves = [];
        $this->selectAll = false;

        if (!$this->classe_id || !$this->annee_source_id) {
            $this->classes_accueil = [];

            return;
        }

        /*
         * On détermine d'abord la classe d'accueil.
         */
        $this->loadClassesAccueil();

        /*
         * Puis les élèves.
         */
        $this->loadEleves();
    }

    /*
    |--------------------------------------------------------------------------
    | CHARGER LES ÉLÈVES
    |--------------------------------------------------------------------------
    */

    public function loadEleves()
    {
        /*
         * Toujours garder $eleves comme Collection.
         */
        $this->eleves = collect();

        /*
         * Toujours utiliser camelCase.
         */
        $this->elevesData = [];

        $this->selected_eleves = [];
        $this->selectAll = false;

        if (
            !$this->annee_source_id ||
            !$this->classe_id
        ) {
            return;
        }

        /*
         * Récupération des inscriptions de la classe
         * pour l'année source.
         */
        $this->eleves = Inscription::query()
            ->with([
                'eleve',
                'classe',
            ])
            ->where('annee_id', $this->annee_source_id)
            ->where('classe_id', $this->classe_id)
            ->orderBy('id')
            ->get();

        /*
         * Calcul des informations de chaque élève.
         */
        foreach ($this->eleves as $inscription) {

            $moyenneAnnuelle = Moyenne::query()
                ->where('inscription_id', $inscription->id)
                ->where('annee_id', $this->annee_source_id)
                ->whereNotNull('moyenne_annuelle')
                ->orderByDesc('trimestre_id')
                ->value('moyenne_annuelle');

            /*
             * Si aucune moyenne annuelle n'existe,
             * on regarde directement celle de l'inscription.
             */
            if ($moyenneAnnuelle === null) {
                $moyenneAnnuelle = $inscription->moyenne_annuelle ?? null;
            }

            $moyenneAnnuelle = $moyenneAnnuelle !== null
                ? (float) $moyenneAnnuelle
                : null;

            /*
             * Décision enregistrée dans l'inscription.
             */
            $decisionBase = $this->normaliserDecision(
                $inscription->decision ?? null
            );

            /*
             * Calcul de la décision.
             */
            if ($decisionBase) {

                $decisionCalculee = $decisionBase;

            } elseif ($moyenneAnnuelle !== null) {

                $decisionCalculee =
                    $moyenneAnnuelle >= $this->seuil_passage
                        ? 'passant'
                        : 'redoublant';

            } else {

                $decisionCalculee = 'non_calculée';
            }

            /*
             * Destination initiale.
             */
            if ($decisionCalculee === 'redoublant') {

                /*
                 * IMPORTANT :
                 * Un redoublant reste dans sa classe source
                 * mais dans l'année d'accueil.
                 */
                $destination = $inscription->classe
                    ? $inscription->classe->nom
                    : 'Même classe';

            } elseif ($decisionCalculee === 'passant') {

                if ($this->classe_accueil_id) {

                    $classeAccueil = Classe::find(
                        $this->classe_accueil_id
                    );

                    $destination = $classeAccueil
                        ? $classeAccueil->nom
                        : 'Classe supérieure';

                } else {

                    $destination = 'Classe supérieure';
                }

            } else {

                $destination = 'Aucun traitement';
            }

            /*
             * Toutes les données sont stockées ici.
             *
             * ATTENTION :
             * on utilise $elevesData et non $eleves_data.
             */
            $this->elevesData[$inscription->id] = [
                'inscription_id' => $inscription->id,
                'eleve_id' => $inscription->eleve_id,
                'classe_id' => $inscription->classe_id,
                'annee_id' => $this->annee_source_id,

                'moyenne' => $moyenneAnnuelle,
                'moyenne_annuelle' => $moyenneAnnuelle,

                'decision' => $decisionCalculee,
                'decision_base' => $decisionBase,

                'destination' => $destination,
            ];
        }

        /*
         * Une seule classe d'accueil :
         * on recalcule les destinations après avoir
         * déterminé automatiquement classe_accueil_id.
         */
        $this->mettreAJourDestinations();
    }

    /*
    |--------------------------------------------------------------------------
    | CALCUL DES DONNÉES
    |--------------------------------------------------------------------------
    */

    public function calculerDonneesEleves()
    {
        /*
         * Cette méthode est conservée pour compatibilité
         * avec le Blade ou les appels existants.
         *
         * Elle reconstruit les données sans transformer
         * $eleves en tableau.
         */

        if (!$this->eleves || $this->eleves->count() === 0) {
            return;
        }

        $this->elevesData = [];

        foreach ($this->eleves as $inscription) {

            $moyenneAnnuelle = Moyenne::query()
                ->where('inscription_id', $inscription->id)
                ->where('annee_id', $this->annee_source_id)
                ->whereNotNull('moyenne_annuelle')
                ->orderByDesc('trimestre_id')
                ->value('moyenne_annuelle');

            if ($moyenneAnnuelle === null) {
                $moyenneAnnuelle = $inscription->moyenne_annuelle ?? null;
            }

            $moyenneAnnuelle = $moyenneAnnuelle !== null
                ? (float) $moyenneAnnuelle
                : null;

            $decisionBase = $this->normaliserDecision(
                $inscription->decision ?? null
            );

            if ($decisionBase) {

                $decision = $decisionBase;

            } elseif ($moyenneAnnuelle !== null) {

                $decision = $moyenneAnnuelle >= $this->seuil_passage
                    ? 'passant'
                    : 'redoublant';

            } else {

                $decision = 'non_calculée';
            }

            $destination = 'Aucun traitement';

            if ($decision === 'redoublant') {

                $destination = $inscription->classe
                    ? $inscription->classe->nom
                    : 'Même classe';

            } elseif ($decision === 'passant') {

                if ($this->classe_accueil_id) {

                    $classeAccueil = Classe::find(
                        $this->classe_accueil_id
                    );

                    $destination = $classeAccueil
                        ? $classeAccueil->nom
                        : 'Classe supérieure';

                } else {

                    $destination = 'Classe supérieure';
                }
            }

            $this->elevesData[$inscription->id] = [
                'inscription_id' => $inscription->id,
                'eleve_id' => $inscription->eleve_id,
                'classe_id' => $inscription->classe_id,
                'annee_id' => $this->annee_source_id,

                'moyenne' => $moyenneAnnuelle,
                'moyenne_annuelle' => $moyenneAnnuelle,

                'decision' => $decision,
                'decision_base' => $decisionBase,

                'destination' => $destination,
            ];
        }

        $this->mettreAJourDestinations();
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALISER UNE DÉCISION
    |--------------------------------------------------------------------------
    */

    protected function normaliserDecision($decision)
    {
        if (!$decision) {
            return null;
        }

        $decision = mb_strtolower(
            trim((string) $decision)
        );

        /*
         * Passant.
         */
        if (
            in_array($decision, [
                'passant',
                'passé',
                'passe',
                'admis',
                'admis(e)',
                'admis_e',
                'promu',
                'promotion',
            ], true)
        ) {
            return 'passant';
        }

        /*
         * Redoublant.
         */
        if (
            in_array($decision, [
                'redoublant',
                'redoublé',
                'redouble',
                'redoublé(e)',
                'redoublement',
                'redoublee',
            ], true)
        ) {
            return 'redoublant';
        }

        /*
         * Exclu.
         */
        if (
            in_array($decision, [
                'exclu',
                'exclue',
                'exclusion',
            ], true)
        ) {
            return 'exclu';
        }

        /*
         * Abandon.
         */
        if (
            in_array($decision, [
                'abandon',
                'abandonné',
                'abandonne',
                'abandonnée',
            ], true)
        ) {
            return 'abandon';
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | CHARGER LES CLASSES D'ACCUEIL
    |--------------------------------------------------------------------------
    */

    public function loadClassesAccueil()
    {
        $this->classes_accueil = [];
        $this->classe_accueil_id = null;

        if (
            !$this->annee_accueil_id ||
            !$this->classe_id
        ) {
            return;
        }

        $classeSource = Classe::find($this->classe_id);

        if (!$classeSource) {
            return;
        }

        /*
         * Classe suivante dans le même cycle.
         *
         * Le champ rang est utilisé pour déterminer
         * le niveau immédiatement supérieur.
         */
        $query = Classe::query()
            ->where('cycle_id', $classeSource->cycle_id)
            ->where('rang', '>', $classeSource->rang)
            ->orderBy('rang');

        /*
         * Classes réellement rattachées à l'année d'accueil.
         */
        $query->whereHas('annees', function ($q) {
            $q->where('annees.id', $this->annee_accueil_id);
        });

        $classes = $query->get();

        /*
         * On prend uniquement le niveau immédiatement supérieur.
         */
        if ($classes->count() > 0) {

            $rangSuivant = $classes->first()->rang;

            $classes = $classes
                ->where('rang', $rangSuivant)
                ->values();
        }

        $this->classes_accueil = $classes;

        /*
         * S'il n'y a qu'une classe possible,
         * elle est automatiquement sélectionnée.
         */
        if ($classes->count() === 1) {

            $this->classe_accueil_id =
                $classes->first()->id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CLASSE D'ACCUEIL MODIFIÉE
    |--------------------------------------------------------------------------
    */

    public function updatedClasseAccueilId()
    {
        $this->mettreAJourDestinations();
    }

    /*
    |--------------------------------------------------------------------------
    | METTRE À JOUR LES DESTINATIONS
    |--------------------------------------------------------------------------
    */

    public function mettreAJourDestinations()
    {
        if (!$this->elevesData) {
            return;
        }

        $classeAccueil = null;

        if ($this->classe_accueil_id) {
            $classeAccueil = Classe::find(
                $this->classe_accueil_id
            );
        }

        foreach ($this->elevesData as $id => $data) {

            /*
             * REDOUBLEMENT
             *
             * Toujours la classe source.
             */
            if (
                ($data['decision'] ?? null)
                === 'redoublant'
            ) {

                $classeSource = Classe::find(
                    $data['classe_id']
                );

                $this->elevesData[$id]['destination'] =
                    $classeSource
                        ? $classeSource->nom
                        : 'Même classe';

                continue;
            }

            /*
             * PASSAGE
             */
            if (
                ($data['decision'] ?? null)
                === 'passant'
            ) {

                $this->elevesData[$id]['destination'] =
                    $classeAccueil
                        ? $classeAccueil->nom
                        : 'Classe supérieure';

                continue;
            }

            /*
             * Autres décisions.
             */
            $this->elevesData[$id]['destination'] =
                'Aucun traitement';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SÉLECTIONNER / DÉSÉLECTIONNER TOUS
    |--------------------------------------------------------------------------
    */

    public function updatedSelectAll($value)
    {
        if ($value) {

            $this->selected_eleves =
                $this->eleves
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->toArray();

        } else {

            $this->selected_eleves = [];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SÉLECTION DES ÉLÈVES
    |--------------------------------------------------------------------------
    */

    public function updatedSelectedEleves()
    {
        /*
         * On ne modifie pas les données calculées ici.
         */
    }

    /*
    |--------------------------------------------------------------------------
    | ÉTAPE SUIVANTE
    |--------------------------------------------------------------------------
    */

    public function nextStep()
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        /*
         * Étape 1 → 2
         */
        if ($this->step === 1) {

            if (!$this->annee_source_id) {

                $this->errorMessage =
                    "Veuillez sélectionner l'année source.";

                return;
            }

            if (!$this->annee_accueil_id) {

                $this->errorMessage =
                    "Veuillez sélectionner l'année d'accueil.";

                return;
            }

            if ($this->annee_source_id == $this->annee_accueil_id) {

                $this->errorMessage =
                    "L'année source et l'année d'accueil doivent être différentes.";

                return;
            }

            if (!$this->classe_id) {

                $this->errorMessage =
                    "Veuillez sélectionner la classe source.";

                return;
            }

            if (!$this->eleves || $this->eleves->count() === 0) {

                $this->errorMessage =
                    "Aucun élève trouvé dans cette classe.";

                return;
            }

            $this->step = 2;

            return;
        }

        /*
         * Étape 2 → 3
         */
        if ($this->step === 2) {

            if (count($this->selected_eleves) === 0) {

                $this->errorMessage =
                    "Veuillez sélectionner au moins un élève.";

                return;
            }

            $this->step = 3;

            return;
        }

        /*
         * Étape 3 → 4
         */
        if ($this->step === 3) {

            $passants = collect(
                $this->selected_eleves
            )->filter(function ($id) {

                return (
                    isset($this->elevesData[$id])
                    &&
                    ($this->elevesData[$id]['decision'] ?? null)
                        === 'passant'
                );

            })->count();

            if (
                $passants > 0 &&
                !$this->classe_accueil_id
            ) {

                $this->errorMessage =
                    "Veuillez sélectionner la classe d'accueil pour les élèves passants.";

                return;
            }

            $this->step = 4;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ÉTAPE PRÉCÉDENTE
    |--------------------------------------------------------------------------
    */

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRMATION
    |--------------------------------------------------------------------------
    */

    public function confirm()
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        if (count($this->selected_eleves) === 0) {

            $this->errorMessage =
                "Aucun élève n'a été sélectionné.";

            return;
        }

        /*
         * Vérification des passants.
         */
        $nombrePassants = collect(
            $this->selected_eleves
        )->filter(function ($id) {

            return (
                isset($this->elevesData[$id])
                &&
                ($this->elevesData[$id]['decision'] ?? null)
                    === 'passant'
            );

        })->count();

        if (
            $nombrePassants > 0 &&
            !$this->classe_accueil_id
        ) {

            $this->errorMessage =
                "Une classe d'accueil doit être sélectionnée pour les élèves passants.";

            return;
        }

        $this->executePassage();
    }

    /*
    |--------------------------------------------------------------------------
    | EXÉCUTER LE PASSAGE / REDOUBLEMENT
    |--------------------------------------------------------------------------
    */

    public function executePassage()
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        if (
            !$this->annee_source_id ||
            !$this->annee_accueil_id
        ) {

            $this->errorMessage =
                "Les années source et accueil sont obligatoires.";

            return;
        }

        if (
            $this->annee_source_id ==
            $this->annee_accueil_id
        ) {

            $this->errorMessage =
                "L'année source et l'année d'accueil doivent être différentes.";

            return;
        }

        if (!$this->classe_id) {

            $this->errorMessage =
                "La classe source est obligatoire.";

            return;
        }

        if (count($this->selected_eleves) === 0) {

            $this->errorMessage =
                "Aucun élève sélectionné.";

            return;
        }

        $classeSource = Classe::find(
            $this->classe_id
        );

        if (!$classeSource) {

            $this->errorMessage =
                "La classe source est introuvable.";

            return;
        }

        /*
         * Classe de destination des passants.
         */
        $classeDestination = null;

        if ($this->classe_accueil_id) {

            $classeDestination = Classe::find(
                $this->classe_accueil_id
            );
        }

        try {

            DB::transaction(function () use (
                $classeSource,
                $classeDestination
            ) {

                /*
                 * On récupère uniquement les inscriptions
                 * sélectionnées.
                 */
                $inscriptions = Inscription::query()
                    ->whereIn(
                        'id',
                        array_map(
                            'intval',
                            $this->selected_eleves
                        )
                    )
                    ->where(
                        'annee_id',
                        $this->annee_source_id
                    )
                    ->where(
                        'classe_id',
                        $this->classe_id
                    )
                    ->get();

                foreach ($inscriptions as $inscription) {

                    /*
                     * ==================================================
                     * CORRECTION PRINCIPALE
                     * ==================================================
                     *
                     * AVANT :
                     *
                     * $this->eleves_data[$inscription->id]
                     *
                     * APRÈS :
                     *
                     * $this->elevesData[$inscription->id]
                     *
                     * C'est ce qui empêchait le redoublement
                     * d'être créé.
                     */
                    $data =
                        $this->elevesData[$inscription->id]
                        ?? null;

                    /*
                     * Si les données n'existent pas,
                     * on les recalcule directement.
                     */
                    if (!$data) {

                        $moyenneAnnuelle =
                            Moyenne::query()
                                ->where(
                                    'inscription_id',
                                    $inscription->id
                                )
                                ->where(
                                    'annee_id',
                                    $this->annee_source_id
                                )
                                ->whereNotNull(
                                    'moyenne_annuelle'
                                )
                                ->orderByDesc('trimestre_id')
                                ->value(
                                    'moyenne_annuelle'
                                );

                        if ($moyenneAnnuelle === null) {
                            $moyenneAnnuelle =
                                $inscription->moyenne_annuelle;
                        }

                        $moyenneAnnuelle =
                            $moyenneAnnuelle !== null
                                ? (float) $moyenneAnnuelle
                                : null;

                        $decisionBase =
                            $this->normaliserDecision(
                                $inscription->decision
                            );

                        if ($decisionBase) {

                            $decision =
                                $decisionBase;

                        } elseif (
                            $moyenneAnnuelle !== null
                        ) {

                            $decision =
                                $moyenneAnnuelle >=
                                $this->seuil_passage
                                    ? 'passant'
                                    : 'redoublant';

                        } else {

                            $decision =
                                'non_calculée';
                        }

                        $data = [
                            'inscription_id' =>
                                $inscription->id,

                            'eleve_id' =>
                                $inscription->eleve_id,

                            'classe_id' =>
                                $inscription->classe_id,

                            'annee_id' =>
                                $this->annee_source_id,

                            'moyenne' =>
                                $moyenneAnnuelle,

                            'moyenne_annuelle' =>
                                $moyenneAnnuelle,

                            'decision' =>
                                $decision,

                            'decision_base' =>
                                $decisionBase,
                        ];

                        /*
                         * On conserve aussi les données
                         * nouvellement calculées.
                         */
                        $this->elevesData[
                            $inscription->id
                        ] = $data;
                    }

                    /*
                     * Décision normalisée.
                     */
                    $decision =
                        $this->normaliserDecision(
                            $data['decision'] ?? null
                        );

                    /*
                     * Si la décision n'est pas connue,
                     * on la calcule avec la moyenne.
                     */
                    if (!$decision) {

                        $moyenneAnnuelle =
                            $data['moyenne_annuelle']
                            ?? $data['moyenne']
                            ?? null;

                        if ($moyenneAnnuelle !== null) {

                            $decision =
                                (float) $moyenneAnnuelle >=
                                $this->seuil_passage
                                    ? 'passant'
                                    : 'redoublant';

                        } else {

                            $decision =
                                'non_calculée';
                        }
                    }

                    /*
                     * ==================================================
                     * DÉCISION NON CALCULÉE
                     * ==================================================
                     */
                    if ($decision === 'non_calculée') {

                        /*
                         * On met simplement à jour l'inscription source.
                         * Aucun passage automatique n'est créé.
                         */
                        $inscription->update([
                            'decision' =>
                                'non_calculée',
                        ]);

                        continue;
                    }

                    /*
                     * ==================================================
                     * EXCLU / ABANDON
                     * ==================================================
                     */
                    if (
                        in_array(
                            $decision,
                            ['exclu', 'abandon'],
                            true
                        )
                    ) {

                        $inscription->update([
                            'decision' =>
                                $decision,

                            'moyenne_annuelle' =>
                                $data['moyenne_annuelle']
                                ?? $data['moyenne']
                                ?? null,
                        ]);

                        continue;
                    }

                    /*
                     * ==================================================
                     * DÉTERMINATION DE LA CLASSE D'ACCUEIL
                     * ==================================================
                     */

                    if ($decision === 'redoublant') {

                        /*
                         * IMPORTANT :
                         *
                         * Le redoublant reprend exactement
                         * la classe source, mais dans l'année
                         * d'accueil.
                         *
                         * Exemple :
                         *
                         * 2025-2026 → 4ème
                         *
                         * Redoublant
                         *
                         * 2026-2027 → 4ème
                         */
                        $nouvelleClasseId =
                            $classeSource->id;

                    } elseif ($decision === 'passant') {

                        /*
                         * Le passant va dans la classe supérieure
                         * choisie.
                         */
                        if (!$classeDestination) {

                            throw new \Exception(
                                "Aucune classe d'accueil n'a été définie pour un élève passant."
                            );
                        }

                        $nouvelleClasseId =
                            $classeDestination->id;

                    } else {

                        continue;
                    }

                    /*
                     * ==================================================
                     * RECHERCHER UNE INSCRIPTION EXISTANTE
                     * ==================================================
                     *
                     * Une inscription par élève et par année.
                     */
                    $nouvelleInscription =
                        Inscription::query()
                            ->where(
                                'eleve_id',
                                $inscription->eleve_id
                            )
                            ->where(
                                'annee_id',
                                $this->annee_accueil_id
                            )
                            ->first();

                    /*
                     * Données communes.
                     */
                    $donneesInscription = [

                        /*
                         * Classe d'accueil :
                         *
                         * - classe source si redoublant
                         * - classe supérieure si passant
                         */
                        'classe_id' =>
                            $nouvelleClasseId,

                        /*
                         * Historique :
                         * ancienne classe = classe source.
                         */
                        'ancienne_classe_id' =>
                            $classeSource->id,

                        'date_inscription' =>
                            now(),

                        'passage_auto' =>
                            true,

                        'decision' =>
                            $decision,

                        'moyenne_annuelle' =>
                            $data['moyenne_annuelle']
                            ?? $data['moyenne']
                            ?? null,
                    ];

                    /*
                     * ==================================================
                     * INSCRIPTION EXISTANTE
                     * ==================================================
                     */
                    if ($nouvelleInscription) {

                        /*
                         * On la corrige au lieu de créer
                         * un doublon.
                         *
                         * Cela permet notamment de corriger
                         * une inscription créée précédemment
                         * dans une mauvaise classe.
                         */
                        $nouvelleInscription->update(
                            $donneesInscription
                        );

                    } else {

                        /*
                         * ==================================================
                         * CRÉATION DE LA NOUVELLE INSCRIPTION
                         * ==================================================
                         */
                        Inscription::create(
                            array_merge(
                                [
                                    'eleve_id' =>
                                        $inscription->eleve_id,

                                    'annee_id' =>
                                        $this->annee_accueil_id,
                                ],
                                $donneesInscription
                            )
                        );
                    }

                    /*
                     * ==================================================
                     * MISE À JOUR DE L'INSCRIPTION SOURCE
                     * ==================================================
                     *
                     * On conserve l'inscription source.
                     * On ne la supprime surtout pas.
                     */
                    $inscription->update([
                        'decision' =>
                            $decision,

                        'moyenne_annuelle' =>
                            $data['moyenne_annuelle']
                            ?? $data['moyenne']
                            ?? null,
                    ]);
                }
            });

            /*
             * Message de succès.
             */
            $this->successMessage =
                "Le traitement des passages et des redoublements a été effectué avec succès.";

            /*
             * Recharger l'historique.
             */
            $this->loadHistorique();

            /*
             * Recharger les données.
             */
            $this->loadEleves();

            /*
             * Revenir à l'étape 1.
             */
            $this->step = 1;

            /*
             * Vider la sélection.
             */
            $this->selected_eleves = [];
            $this->selectAll = false;

        } catch (\Throwable $e) {

            /*
             * Annulation automatique de la transaction
             * si une erreur se produit.
             */
            $this->errorMessage =
                "Erreur lors du traitement : "
                . $e->getMessage();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ANNULER UN PASSAGE
    |--------------------------------------------------------------------------
    */

    public function annulerPassage($id)
    {
        try {

            $inscription =
                Inscription::find($id);

            if (!$inscription) {

                $this->errorMessage =
                    "Inscription introuvable.";

                return;
            }

            /*
             * On ne supprime que les inscriptions
             * créées automatiquement par le système.
             */
            if (!$inscription->passage_auto) {

                $this->errorMessage =
                    "Cette inscription ne provient pas d'un passage automatique.";

                return;
            }

            $inscription->delete();

            $this->successMessage =
                "Le passage a été annulé avec succès.";

            $this->loadHistorique();

        } catch (\Throwable $e) {

            $this->errorMessage =
                "Impossible d'annuler le passage : "
                . $e->getMessage();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORIQUE
    |--------------------------------------------------------------------------
    */

    public function loadHistorique()
    {
        if (!$this->annee_accueil_id) {

            $this->historique_passages = collect();

            return;
        }

        $this->historique_passages =
            Inscription::query()
                ->with([
                    'eleve',
                    'classe',
                    'ancienneClasse',
                ])
                ->where(
                    'annee_id',
                    $this->annee_accueil_id
                )
                ->where(
                    'passage_auto',
                    true
                )
                ->orderByDesc('id')
                ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | COMPTEUR : PASSANTS
    |--------------------------------------------------------------------------
    */

    public function getNombrePassantsProperty()
    {
        return collect($this->elevesData)
            ->filter(function ($data) {

                return (
                    ($data['decision'] ?? null)
                    === 'passant'
                );

            })
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | COMPTEUR : REDOUBLANTS
    |--------------------------------------------------------------------------
    */

    public function getNombreRedoublantsProperty()
    {
        return collect($this->elevesData)
            ->filter(function ($data) {

                return (
                    ($data['decision'] ?? null)
                    === 'redoublant'
                );

            })
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | COMPTEUR : NON CALCULÉS
    |--------------------------------------------------------------------------
    */

    public function getNombreNonCalculatesProperty()
    {
        return collect($this->elevesData)
            ->filter(function ($data) {

                return (
                    ($data['decision'] ?? null)
                    === 'non_calculée'
                );

            })
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | RENDU
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $cycles = Cycle::query()
            ->orderBy('nom')
            ->get();

        return view(
            'livewire.passage-wizard',
            [
                'annees' => $annees,
                'cycles' => $cycles,
            ]
        );
    }
}