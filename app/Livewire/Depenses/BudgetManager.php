<?php

namespace App\Livewire\Depenses;

use App\Models\Annee;
use App\Models\BudgetDepense;
use App\Models\CategorieDepense;
use App\Models\Depense;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Gestion des budgets')]
class BudgetManager extends Component
{
    // ============================================================
    // FILTRE
    // ============================================================

    public string $anneeFiltre = '';


    // ============================================================
    // MODALES
    // ============================================================

    public bool $showModal = false;
    public bool $showSuppressionModal = false;
    public bool $modeEdition = false;


    // ============================================================
    // FORMULAIRE
    // ============================================================

    public ?int $budget_id = null;

    public string $categorie_id = '';

    public string $annee_id = '';

    public string $montant_alloue = '';


    // ============================================================
    // SUPPRESSION
    // ============================================================

    public ?int $budgetASupprimer = null;


    // ============================================================
    // MESSAGES
    // ============================================================

    public string $messageSucces = '';

    public string $messageErreur = '';


    // ============================================================
    // VALIDATION
    // ============================================================

    protected function rules(): array
    {
        return [
            'categorie_id' => [
                'required',
                'exists:categories_depenses,id',

                Rule::unique('budgets_depenses', 'categorie_id')
                    ->where(
                        fn ($query) =>
                            $query->where('annee_id', $this->annee_id)
                    )
                    ->ignore($this->budget_id),
            ],

            'annee_id' => [
                'required',
                'exists:annees,id',
            ],

            'montant_alloue' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }


    protected array $messages = [
        'categorie_id.required' =>
            'La catégorie est obligatoire.',

        'categorie_id.exists' =>
            'La catégorie sélectionnée est invalide.',

        'categorie_id.unique' =>
            'Un budget existe déjà pour cette catégorie sur cette année scolaire.',

        'annee_id.required' =>
            'L’année scolaire est obligatoire.',

        'annee_id.exists' =>
            'L’année scolaire sélectionnée est invalide.',

        'montant_alloue.required' =>
            'Le montant alloué est obligatoire.',

        'montant_alloue.numeric' =>
            'Le montant alloué doit être un nombre valide.',

        'montant_alloue.min' =>
            'Le montant alloué doit être supérieur à zéro.',
    ];


    // ============================================================
    // INITIALISATION
    // ============================================================

    public function mount(): void
    {
        $anneeEnCours = Annee::where('en_cours', true)->first();

        $this->anneeFiltre = (string) ($anneeEnCours->id ?? '');
    }


    // ============================================================
    // CATÉGORIES DISPONIBLES
    // ============================================================

    public function getCategoriesDisponiblesProperty()
    {
        $dejaBudgetisees = BudgetDepense::query()
            ->where('annee_id', $this->annee_id)
            ->when(
                $this->modeEdition,
                fn ($q) =>
                    $q->where('id', '!=', $this->budget_id)
            )
            ->pluck('categorie_id');

        return CategorieDepense::actif()
            ->whereNotIn('id', $dejaBudgetisees)
            ->orderBy('nom')
            ->get();
    }


    // ============================================================
    // CALCUL DU MONTANT UTILISÉ
    // ============================================================
    /**
     * Retourne les dépenses validées regroupées par catégorie.
     *
     * Relation :
     *
     * depenses.type_depense_id
     *      ↓
     * types_depenses.id
     *      ↓
     * types_depenses.categorie_id
     *      ↓
     * categories_depenses.id
     *
     * Seules les dépenses avec statut = "validee"
     * sont prises en compte.
     */
    protected function montantsUtilisesParCategorie(?string $anneeId): array
    {
        if (!$anneeId) {
            return [];
        }

        return Depense::query()
            ->join(
                'types_depenses as td',
                'td.id',
                '=',
                'depenses.type_depense_id'
            )
            ->where('depenses.annee_id', $anneeId)
            ->where('depenses.statut', 'validee')
            ->whereNotNull('td.categorie_id')
            ->select(
                'td.categorie_id',
                DB::raw('SUM(depenses.montant) as montant_utilise')
            )
            ->groupBy('td.categorie_id')
            ->pluck('montant_utilise', 'td.categorie_id')
            ->map(fn ($montant) => (float) $montant)
            ->toArray();
    }


    // ============================================================
    // OUVRIR MODALE CRÉATION
    // ============================================================

    public function ouvrirModalCreation(): void
    {
        $this->resetFormulaire();

        $this->annee_id = $this->anneeFiltre;

        $this->modeEdition = false;

        $this->messageSucces = '';
        $this->messageErreur = '';

        $this->showModal = true;
    }


    // ============================================================
    // OUVRIR MODALE ÉDITION
    // ============================================================

    public function ouvrirModalEdition(int $id): void
    {
        $budget = BudgetDepense::findOrFail($id);

        $this->budget_id = $budget->id;

        $this->categorie_id = (string) $budget->categorie_id;

        $this->annee_id = (string) $budget->annee_id;

        $this->montant_alloue = (string) $budget->montant_alloue;

        $this->modeEdition = true;

        $this->messageSucces = '';
        $this->messageErreur = '';

        $this->showModal = true;
    }


    // ============================================================
    // FERMER MODALE
    // ============================================================

    public function fermerModal(): void
    {
        $this->showModal = false;

        $this->resetFormulaire();
    }


    // ============================================================
    // RESET FORMULAIRE
    // ============================================================

    protected function resetFormulaire(): void
    {
        $this->reset([
            'budget_id',
            'categorie_id',
            'annee_id',
            'montant_alloue',
        ]);

        $this->resetErrorBag();
    }


    // ============================================================
    // ENREGISTRER
    // ============================================================

    public function enregistrer(): void
    {
        $this->messageSucces = '';
        $this->messageErreur = '';

        $this->validate();

        if ($this->modeEdition) {

            $budget = BudgetDepense::findOrFail($this->budget_id);

            /*
             * On ne touche PAS à montant_utilise ici.
             *
             * Il est calculé à partir des dépenses réellement
             * validées.
             */
            $budget->update([
                'categorie_id' => $this->categorie_id,
                'annee_id' => $this->annee_id,
                'montant_alloue' => $this->montant_alloue,
            ]);

            $this->messageSucces =
                'Budget mis à jour avec succès.';

        } else {

            BudgetDepense::create([
                'categorie_id' => $this->categorie_id,
                'annee_id' => $this->annee_id,
                'montant_alloue' => $this->montant_alloue,

                /*
                 * Valeur initiale uniquement.
                 * Le montant réel est calculé depuis depenses.
                 */
                'montant_utilise' => 0,
            ]);

            $this->messageSucces =
                'Budget créé avec succès.';
        }

        $this->showModal = false;

        $this->resetFormulaire();
    }


    // ============================================================
    // CONFIRMATION SUPPRESSION
    // ============================================================

    public function confirmerSuppression(int $id): void
    {
        $this->budgetASupprimer = $id;

        $this->messageSucces = '';
        $this->messageErreur = '';

        $this->showSuppressionModal = true;
    }


    // ============================================================
    // ANNULER SUPPRESSION
    // ============================================================

    public function annulerSuppression(): void
    {
        $this->budgetASupprimer = null;

        $this->showSuppressionModal = false;
    }


    // ============================================================
    // SUPPRIMER
    // ============================================================

    public function supprimer(): void
    {
        if (!$this->budgetASupprimer) {
            return;
        }

        $budget = BudgetDepense::findOrFail(
            $this->budgetASupprimer
        );

        /*
         * IMPORTANT :
         *
         * On ne fait plus confiance à budgets_depenses.montant_utilise.
         *
         * On recalcule le montant réellement utilisé depuis
         * les dépenses validées.
         */
        $montantsUtilises = $this->montantsUtilisesParCategorie(
            (string) $budget->annee_id
        );

        $montantUtilise =
            (float) ($montantsUtilises[$budget->categorie_id] ?? 0);

        if ($montantUtilise > 0) {

            $this->messageErreur =
                'Impossible de supprimer ce budget : ' .
                number_format($montantUtilise, 0, ',', ' ') .
                ' FCFA ont déjà été utilisés pour cette catégorie.';

            $this->showSuppressionModal = false;

            return;
        }

        $budget->delete();

        $this->messageSucces =
            'Budget supprimé avec succès.';

        $this->showSuppressionModal = false;

        $this->budgetASupprimer = null;
    }


    // ============================================================
    // RENDU
    // ============================================================

    public function render()
    {
        /*
         * --------------------------------------------------------
         * 1. Récupération des budgets
         * --------------------------------------------------------
         */

        $budgets = BudgetDepense::query()
            ->with('categorie')
            ->when(
                $this->anneeFiltre,
                fn ($q) =>
                    $q->where(
                        'annee_id',
                        $this->anneeFiltre
                    )
            )
            ->get();


        /*
         * --------------------------------------------------------
         * 2. Calcul réel des dépenses utilisées
         * --------------------------------------------------------
         */

        $montantsUtilises = $this->montantsUtilisesParCategorie(
            $this->anneeFiltre
        );


        /*
         * --------------------------------------------------------
         * 3. Injection du montant utilisé dans chaque budget
         * --------------------------------------------------------
         *
         * IMPORTANT :
         * Cette valeur vient maintenant de depenses.validée,
         * et non plus de budgets_depenses.montant_utilise.
         */

        foreach ($budgets as $budget) {

            $budget->montant_utilise =
                (float) (
                    $montantsUtilises[$budget->categorie_id] ?? 0
                );

            $budget->montant_alloue =
                (float) $budget->montant_alloue;

            $budget->montant_restant =
                $budget->montant_alloue
                - $budget->montant_utilise;

            $budget->taux_utilisation =
                $budget->montant_alloue > 0
                    ? round(
                        (
                            $budget->montant_utilise
                            / $budget->montant_alloue
                        ) * 100,
                        2
                    )
                    : 0;
        }


        /*
         * --------------------------------------------------------
         * 4. Tri par catégorie
         * --------------------------------------------------------
         */

        $budgets = $budgets
            ->sortBy(
                fn ($budget) =>
                    $budget->categorie?->nom ?? ''
            )
            ->values();


        /*
         * --------------------------------------------------------
         * 5. Totaux
         * --------------------------------------------------------
         */

        $totalAlloue = (float) $budgets->sum(
            'montant_alloue'
        );

        $totalUtilise = (float) $budgets->sum(
            'montant_utilise'
        );

        $totalRestant =
            $totalAlloue - $totalUtilise;

        $tauxGlobal =
            $totalAlloue > 0
                ? round(
                    ($totalUtilise / $totalAlloue) * 100,
                    2
                )
                : 0;


        /*
         * --------------------------------------------------------
         * 6. Années
         * --------------------------------------------------------
         */

        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();


        /*
         * --------------------------------------------------------
         * 7. Vue
         * --------------------------------------------------------
         */

        return view(
            'livewire.depenses.budget-manager',
            [
                'budgets' => $budgets,

                'annees' => $annees,

                'totalAlloue' => $totalAlloue,

                'totalUtilise' => $totalUtilise,

                'totalRestant' => $totalRestant,

                'tauxGlobal' => $tauxGlobal,
            ]
        );
    }
}