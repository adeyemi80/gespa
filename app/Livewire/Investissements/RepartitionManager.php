<?php

namespace App\Livewire\Investissements;

use App\Models\Benefice;
use App\Models\Investissement;
use App\Models\Repartition;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class RepartitionManager extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    // ------------------------------------------------------------------
    // Filtre "par bénéfice" (remplace la route parBenefice)
    // ------------------------------------------------------------------
    public ?int $beneficeFiltre = null;

    // ------------------------------------------------------------------
    // Modal de calcul automatique (create + calculer)
    // ------------------------------------------------------------------
    public bool $showCalculModal = false;
    public ?int $benefice_id = null;

    // ------------------------------------------------------------------
    // Modal édition (pourcentage / montant)
    // ------------------------------------------------------------------
    public bool $showEditModal = false;
    public ?int $editingId = null;
    public ?float $pourcentage = null;
    public ?float $montant = null;

    // ------------------------------------------------------------------
    // Modal détail (show)
    // ------------------------------------------------------------------
    public bool $showDetailModal = false;
    public ?Repartition $repartitionSelectionnee = null;

    public function updatingBeneficeFiltre(): void
    {
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Bénéfices disponibles pour les selects (mémorisé)
    // ------------------------------------------------------------------
    #[Computed]
    public function benefices()
    {
        return Benefice::latest()->get();
    }

    // ------------------------------------------------------------------
    // Modal de calcul automatique
    // ------------------------------------------------------------------
    public function ouvrirCalcul(): void
    {
        $this->resetValidation();
        $this->reset(['benefice_id']);
        $this->showCalculModal = true;
    }

    public function fermerCalculModal(): void
    {
        $this->showCalculModal = false;
        $this->resetValidation();
        $this->reset(['benefice_id']);
    }

    /**
     * Calcul automatique des répartitions (reprend calculer() à l'identique)
     */
    public function calculer(): void
    {
        $this->validate([
            'benefice_id' => ['required', 'exists:benefices,id'],
        ], [
            'benefice_id.required' => 'Veuillez sélectionner un bénéfice.',
            'benefice_id.exists' => 'Le bénéfice sélectionné est invalide.',
        ]);

        try {

            DB::transaction(function () {

                $benefice = Benefice::findOrFail($this->benefice_id);

                // Suppression ancienne répartition
                Repartition::where('benefice_id', $benefice->id)->delete();

                // Capital total investi (investissements actifs)
                $capitalTotal = Investissement::where('statut', 'actif')->sum('montant');

                if ($capitalTotal <= 0) {
                    throw new \Exception('Aucun investissement actif trouvé.');
                }

                // Calcul par investisseur
                $investissements = Investissement::with('investisseur')
                    ->where('statut', 'actif')
                    ->get();

                foreach ($investissements as $investissement) {

                    $pourcentage = ($investissement->montant / $capitalTotal) * 100;
                    $montant = ($pourcentage / 100) * $benefice->montant;

                    Repartition::create([
                        'benefice_id' => $benefice->id,
                        'investissement_id' => $investissement->id,
                        'pourcentage' => round($pourcentage, 4),
                        'montant' => round($montant, 2),
                    ]);

                }

            });

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Répartition calculée avec succès.'
            );

            $this->fermerCalculModal();
            $this->resetPage();

        } catch (\Exception $e) {

            $this->dispatch(
                'notify',
                type: 'error',
                message: $e->getMessage()
            );

        }
    }

    // ------------------------------------------------------------------
    // Édition (pourcentage / montant)
    // ------------------------------------------------------------------
    public function ouvrirEdition(int $id): void
    {
        $repartition = Repartition::findOrFail($id);

        $this->resetValidation();
        $this->editingId = $repartition->id;
        $this->pourcentage = $repartition->pourcentage;
        $this->montant = $repartition->montant;

        $this->showEditModal = true;
    }

    public function fermerEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetValidation();
        $this->reset(['editingId', 'pourcentage', 'montant']);
    }

    public function enregistrerEdition(): void
    {
        $validated = $this->validate([
            'pourcentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'montant' => ['required', 'numeric', 'min:0'],
        ]);

        $repartition = Repartition::findOrFail($this->editingId);
        $repartition->update($validated);

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Répartition modifiée.'
        );

        $this->fermerEditModal();
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Suppression
    // ------------------------------------------------------------------
    public function supprimer(int $id): void
    {
        Repartition::findOrFail($id)->delete();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Répartition supprimée.'
        );

        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Affichage détaillé (show)
    // ------------------------------------------------------------------
    public function voirDetail(int $id): void
    {
        $this->repartitionSelectionnee = Repartition::with([
            'benefice',
            'investissement.investisseur',
        ])->findOrFail($id);

        $this->showDetailModal = true;
    }

    public function fermerDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->repartitionSelectionnee = null;
    }

    public function render()
    {
        $repartitions = Repartition::with([
            'benefice',
            'investissement.investisseur',
        ])
            ->when($this->beneficeFiltre, function ($query) {
                $query->where('benefice_id', $this->beneficeFiltre);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.investissements.repartition-manager', [
            'repartitions' => $repartitions,
        ]);
    }
}