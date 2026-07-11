<?php

namespace App\Livewire\Investissements;

use App\Models\Benefice;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class BeneficeManager extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    // ------------------------------------------------------------------
    // Filtres (index)
    // ------------------------------------------------------------------
    public ?string $filtreDateDebut = null;
    public ?string $filtreDateFin = null;

    // ------------------------------------------------------------------
    // Mode d'affichage : 'liste' | 'historique'
    // ------------------------------------------------------------------
    public string $mode = 'liste';

    // ------------------------------------------------------------------
    // Modal création / édition
    // ------------------------------------------------------------------
    public bool $showFormModal = false;
    public ?int $editingId = null;

    public ?string $date_debut = null;
    public ?string $date_fin = null;
    public ?float $montant = null;
    public ?string $observation = null;

    // ------------------------------------------------------------------
    // Modal détail (show)
    // ------------------------------------------------------------------
    public bool $showDetailModal = false;
    public ?Benefice $beneficeSelectionne = null;

    protected function rules(): array
    {
        return [
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            'montant' => ['required', 'numeric', 'min:0'],
            'observation' => ['nullable', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant ne peut pas être négatif.',
        ];
    }

    // ------------------------------------------------------------------
    // Réinitialise la pagination quand les filtres changent
    // ------------------------------------------------------------------
    public function updatingFiltreDateDebut(): void
    {
        $this->resetPage();
    }

    public function updatingFiltreDateFin(): void
    {
        $this->resetPage();
    }

    public function resetFiltres(): void
    {
        $this->reset(['filtreDateDebut', 'filtreDateFin']);
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Bascule entre liste (paginée) et historique (complet)
    // ------------------------------------------------------------------
    public function afficherHistorique(): void
    {
        $this->mode = 'historique';
    }

    public function afficherListe(): void
    {
        $this->mode = 'liste';
    }

    // ------------------------------------------------------------------
    // Création
    // ------------------------------------------------------------------
    public function ouvrirCreation(): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'date_debut', 'date_fin', 'montant', 'observation']);
        $this->showFormModal = true;
    }

    // ------------------------------------------------------------------
    // Édition
    // ------------------------------------------------------------------
    public function ouvrirEdition(int $id): void
    {
        $benefice = Benefice::findOrFail($id);

        $this->resetValidation();
        $this->editingId = $benefice->id;
        $this->date_debut = optional($benefice->date_debut)->format('Y-m-d') ?? $benefice->date_debut;
        $this->date_fin = optional($benefice->date_fin)->format('Y-m-d') ?? $benefice->date_fin;
        $this->montant = $benefice->montant;
        $this->observation = $benefice->observation;

        $this->showFormModal = true;
    }

    public function fermerFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetValidation();
        $this->reset(['editingId', 'date_debut', 'date_fin', 'montant', 'observation']);
    }

    // ------------------------------------------------------------------
    // Enregistrement (création ou mise à jour)
    // ------------------------------------------------------------------
    public function enregistrer(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {

            if ($this->editingId) {

                $benefice = Benefice::findOrFail($this->editingId);
                $benefice->update($validated);

                $this->dispatch(
                    'notify',
                    type: 'success',
                    message: 'Bénéfice modifié avec succès.'
                );

            } else {

                Benefice::create($validated);

                $this->dispatch(
                    'notify',
                    type: 'success',
                    message: 'Bénéfice enregistré avec succès.'
                );
            }

        });

        $this->fermerFormModal();
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Suppression (avec ses répartitions)
    // ------------------------------------------------------------------
    public function supprimer(int $id): void
    {
        DB::transaction(function () use ($id) {

            $benefice = Benefice::findOrFail($id);

            // Suppression des répartitions associées si elles existent
            $benefice->repartitions()->delete();

            $benefice->delete();

        });

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Bénéfice supprimé avec succès.'
        );

        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Affichage détaillé (show)
    // ------------------------------------------------------------------
    public function voirDetail(int $id): void
    {
        $this->beneficeSelectionne = Benefice::with([
            'repartitions.investissement.investisseur',
        ])->findOrFail($id);

        $this->showDetailModal = true;
    }

    public function fermerDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->beneficeSelectionne = null;
    }

    // ------------------------------------------------------------------
    // Total général des bénéfices (calculé à la volée, comme total())
    // ------------------------------------------------------------------
    #[Computed]
    public function total()
    {
        return Benefice::sum('montant');
    }

    public function render()
    {
        $benefices = null;
        $historique = null;

        if ($this->mode === 'historique') {

            $historique = Benefice::with(['repartitions'])
                ->orderBy('date_fin', 'desc')
                ->get();

        } else {

            $benefices = Benefice::query()
                ->when($this->filtreDateDebut, function ($query) {
                    $query->whereDate('date_debut', '>=', $this->filtreDateDebut);
                })
                ->when($this->filtreDateFin, function ($query) {
                    $query->whereDate('date_fin', '<=', $this->filtreDateFin);
                })
                ->orderBy('date_fin', 'desc')
                ->paginate(15);

        }

        return view('livewire.investissements.benefice-manager', [
            'benefices' => $benefices,
            'historique' => $historique,
        ]);
    }
}