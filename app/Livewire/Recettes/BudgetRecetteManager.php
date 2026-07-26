<?php

namespace App\Livewire\Recettes;

use App\Models\Annee;
use App\Models\BudgetRecette;
use App\Models\CategorieRecette;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('tableau.neutre')]
#[Title('Budget des recettes')]
class BudgetRecetteManager extends Component
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
    public string $montant_prevu = '';

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
                'exists:categories_recettes,id',
                Rule::unique('budgets_recettes', 'categorie_id')
                    ->where(fn ($query) => $query->where('annee_id', $this->annee_id))
                    ->ignore($this->budget_id),
            ],
            'annee_id' => 'required|exists:annees,id',
            'montant_prevu' => 'required|numeric|min:0.01',
        ];
    }

    protected array $messages = [
        'categorie_id.required' => 'La catégorie est obligatoire.',
        'categorie_id.unique' => 'Un budget existe déjà pour cette catégorie sur cette année scolaire.',
        'annee_id.required' => 'L\'année scolaire est obligatoire.',
        'montant_prevu.required' => 'Le montant prévu est obligatoire.',
        'montant_prevu.min' => 'Le montant prévu doit être supérieur à zéro.',
    ];

    public function mount(): void
    {
        $anneeEnCours = Annee::where('en_cours', true)->first();
        $this->anneeFiltre = (string) ($anneeEnCours->id ?? '');
    }

    public function getCategoriesDisponiblesProperty()
    {
        $dejaBudgetisees = BudgetRecette::where('annee_id', $this->annee_id)
            ->when($this->modeEdition, fn ($q) => $q->where('id', '!=', $this->budget_id))
            ->pluck('categorie_id');

        return CategorieRecette::actif()
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
        $budget = BudgetRecette::findOrFail($id);

        $this->budget_id = $budget->id;
        $this->categorie_id = (string) $budget->categorie_id;
        $this->annee_id = (string) $budget->annee_id;
        $this->montant_prevu = (string) $budget->montant_prevu;

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
        $this->reset(['budget_id', 'categorie_id', 'annee_id', 'montant_prevu']);
        $this->resetErrorBag();
    }

    public function enregistrer(): void
    {
        $this->validate();

        if ($this->modeEdition) {
            $budget = BudgetRecette::findOrFail($this->budget_id);
            $budget->update([
                'categorie_id' => $this->categorie_id,
                'annee_id' => $this->annee_id,
                'montant_prevu' => $this->montant_prevu,
            ]);
            $this->messageSucces = 'Budget de recette mis à jour avec succès.';
        } else {
            BudgetRecette::create([
                'categorie_id' => $this->categorie_id,
                'annee_id' => $this->annee_id,
                'montant_prevu' => $this->montant_prevu,
                'montant_realise' => 0,
            ]);
            $this->messageSucces = 'Budget de recette créé avec succès.';
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
        $budget = BudgetRecette::findOrFail($this->budgetASupprimer);

        if ((float) $budget->montant_realise > 0) {
            $this->messageErreur = 'Impossible de supprimer un budget déjà partiellement ou totalement réalisé.';
            $this->showSuppressionModal = false;
            return;
        }

        $budget->delete();

        $this->messageSucces = 'Budget de recette supprimé avec succès.';
        $this->showSuppressionModal = false;
        $this->budgetASupprimer = null;
    }

    public function render()
    {
        $budgets = BudgetRecette::query()
            ->with('categorie')
            ->when($this->anneeFiltre, fn ($q) => $q->where('annee_id', $this->anneeFiltre))
            ->get()
            ->sortBy(fn ($budget) => $budget->categorie->nom);

        $totalPrevu = $budgets->sum('montant_prevu');
        $totalRealise = $budgets->sum('montant_realise');
        $tauxGlobal = $totalPrevu > 0 ? round(($totalRealise / $totalPrevu) * 100, 2) : 0;

        return view('livewire.recettes.budget-recette-manager', [
            'budgets' => $budgets,
            'annees' => Annee::orderByDesc('id')->get(),
            'totalPrevu' => $totalPrevu,
            'totalRealise' => $totalRealise,
            'totalEcart' => $totalRealise - $totalPrevu,
            'tauxGlobal' => $tauxGlobal,
        ]);
    }
}