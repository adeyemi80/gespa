<?php

namespace App\Livewire\Depenses;

use App\Models\Annee;
use App\Models\BudgetDepense;
use App\Models\CategorieDepense;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Gestion des budgets')]
class BudgetManager extends Component
{
    // Filtre
    public string $anneeFiltre = '';

    // État des modales
    public bool $showModal = false;
    public bool $showSuppressionModal = false;
    public bool $modeEdition = false;

    // Champs du formulaire
    public ?int $budget_id = null;
    public string $categorie_id = '';
    public string $annee_id = '';
    public string $montant_alloue = '';

    // Suppression
    public ?int $budgetASupprimer = null;

    // Messages (propriétés de composant, pas session()->flash())
    public string $messageSucces = '';
    public string $messageErreur = '';

    protected function rules(): array
    {
        return [
            'categorie_id' => [
                'required',
                'exists:categories_depenses,id',
                Rule::unique('budgets_depenses', 'categorie_id')
                    ->where(fn ($query) => $query->where('annee_id', $this->annee_id))
                    ->ignore($this->budget_id),
            ],
            'annee_id' => 'required|exists:annees,id',
            'montant_alloue' => 'required|numeric|min:0.01',
        ];
    }

    protected array $messages = [
        'categorie_id.required' => 'La catégorie est obligatoire.',
        'categorie_id.unique' => 'Un budget existe déjà pour cette catégorie sur cette année scolaire.',
        'annee_id.required' => 'L\'année scolaire est obligatoire.',
        'montant_alloue.required' => 'Le montant alloué est obligatoire.',
        'montant_alloue.min' => 'Le montant alloué doit être supérieur à zéro.',
    ];

    public function mount(): void
    {
        $anneeEnCours = Annee::where('en_cours', true)->first();
        $this->anneeFiltre = (string) ($anneeEnCours->id ?? '');
    }

    public function getCategoriesDisponiblesProperty()
    {
        $dejaBudgetisees = BudgetDepense::where('annee_id', $this->annee_id)
            ->when($this->modeEdition, fn ($q) => $q->where('id', '!=', $this->budget_id))
            ->pluck('categorie_id');

        return CategorieDepense::actif()
            ->whereNotIn('id', $dejaBudgetisees)
            ->orderBy('nom')
            ->get();
    }

    public function ouvrirModalCreation(): void
    {
        $this->resetFormulaire();
        $this->annee_id = $this->anneeFiltre;
        $this->modeEdition = false;
        $this->showModal = true;
    }

    public function ouvrirModalEdition(int $id): void
    {
        $budget = BudgetDepense::findOrFail($id);

        $this->budget_id = $budget->id;
        $this->categorie_id = (string) $budget->categorie_id;
        $this->annee_id = (string) $budget->annee_id;
        $this->montant_alloue = (string) $budget->montant_alloue;

        $this->modeEdition = true;
        $this->showModal = true;
    }

    public function fermerModal(): void
    {
        $this->showModal = false;
        $this->resetFormulaire();
    }

    protected function resetFormulaire(): void
    {
        $this->reset(['budget_id', 'categorie_id', 'annee_id', 'montant_alloue']);
        $this->resetErrorBag();
    }

    public function enregistrer(): void
    {
        $this->validate();

        if ($this->modeEdition) {
            $budget = BudgetDepense::findOrFail($this->budget_id);
            $budget->update([
                'categorie_id' => $this->categorie_id,
                'annee_id' => $this->annee_id,
                'montant_alloue' => $this->montant_alloue,
            ]);
            $this->messageSucces = 'Budget mis à jour avec succès.';
        } else {
            BudgetDepense::create([
                'categorie_id' => $this->categorie_id,
                'annee_id' => $this->annee_id,
                'montant_alloue' => $this->montant_alloue,
                'montant_utilise' => 0,
            ]);
            $this->messageSucces = 'Budget créé avec succès.';
        }

        $this->showModal = false;
        $this->resetFormulaire();
    }

    public function confirmerSuppression(int $id): void
    {
        $this->budgetASupprimer = $id;
        $this->showSuppressionModal = true;
    }

    public function annulerSuppression(): void
    {
        $this->budgetASupprimer = null;
        $this->showSuppressionModal = false;
    }

    public function supprimer(): void
    {
        $budget = BudgetDepense::findOrFail($this->budgetASupprimer);

        if ((float) $budget->montant_utilise > 0) {
            $this->messageErreur = 'Impossible de supprimer un budget déjà partiellement ou totalement utilisé.';
            $this->showSuppressionModal = false;
            return;
        }

        $budget->delete();

        $this->messageSucces = 'Budget supprimé avec succès.';
        $this->showSuppressionModal = false;
        $this->budgetASupprimer = null;
    }

    public function render()
    {
        $budgets = BudgetDepense::query()
            ->with('categorie')
            ->when($this->anneeFiltre, fn ($q) => $q->where('annee_id', $this->anneeFiltre))
            ->get()
            ->sortBy(fn ($budget) => $budget->categorie->nom);

        $totalAlloue = $budgets->sum('montant_alloue');
        $totalUtilise = $budgets->sum('montant_utilise');
        $tauxGlobal = $totalAlloue > 0 ? round(($totalUtilise / $totalAlloue) * 100, 2) : 0;

        return view('livewire.depenses.budget-manager', [
            'budgets' => $budgets,
            'annees' => Annee::orderByDesc('id')->get(),
            'totalAlloue' => $totalAlloue,
            'totalUtilise' => $totalUtilise,
            'totalRestant' => $totalAlloue - $totalUtilise,
            'tauxGlobal' => $tauxGlobal,
        ]);
    }
}