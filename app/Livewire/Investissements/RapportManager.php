<?php

namespace App\Livewire\Investissements;

use App\Models\Benefice;
use App\Models\Investissement;
use App\Models\Investisseur;
use App\Models\PaiementBenefice;
use App\Models\Repartition;
use App\Models\RetraitCapital;
use App\Models\Versement;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class RapportManager extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    // ------------------------------------------------------------------
    // Onglet actif : apercu | investisseurs | investissements |
    //                versements | benefices | retraits | situation
    // ------------------------------------------------------------------
    public string $onglet = 'apercu';

    // ------------------------------------------------------------------
    // Filtres de période (communs aux onglets investissements,
    // versements, benefices, retraits)
    // ------------------------------------------------------------------
    public ?string $dateDebut = null;
    public ?string $dateFin = null;

    // ------------------------------------------------------------------
    // Onglet "Situation d'un investisseur"
    // ------------------------------------------------------------------
    public string $rechercheInvestisseur = '';
    public ?int $investisseurId = null;

    public function mount(): void
    {
        // Permet d'arriver directement sur la situation d'un investisseur
        // via ?investisseur=ID, comme l'ancienne route situationInvestisseur($id)
        if (request()->filled('investisseur')) {
            $this->investisseurId = (int) request('investisseur');
            $this->onglet = 'situation';
        }
    }

    // ------------------------------------------------------------------
    // Changement d'onglet
    // ------------------------------------------------------------------
    public function changerOnglet(string $onglet): void
    {
        $this->onglet = $onglet;
        $this->resetPage();
    }

    public function updatingDateDebut(): void
    {
        $this->resetPage();
    }

    public function updatingDateFin(): void
    {
        $this->resetPage();
    }

    public function resetFiltres(): void
    {
        $this->reset(['dateDebut', 'dateFin']);
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Sélection d'un investisseur pour l'onglet "situation"
    // ------------------------------------------------------------------
    public function selectionnerInvestisseur(int $id): void
    {
        $this->investisseurId = $id;
        $this->rechercheInvestisseur = '';
    }

    public function changerInvestisseur(): void
    {
        $this->investisseurId = null;
    }

    // ------------------------------------------------------------------
    // Résultats de recherche d'investisseur (onglet situation)
    // ------------------------------------------------------------------
    #[Computed]
    public function suggestionsInvestisseurs()
    {
        if (blank($this->rechercheInvestisseur)) {
            return collect();
        }

        return Investisseur::query()
            ->where(function ($q) {
                $search = $this->rechercheInvestisseur;

                $q->where('nom', 'ILIKE', "%{$search}%")
                    ->orWhere('prenom', 'ILIKE', "%{$search}%")
                    ->orWhere('telephone', 'ILIKE', "%{$search}%");
            })
            ->orderBy('nom')
            ->limit(10)
            ->get();
    }

    // ------------------------------------------------------------------
    // Vue d'ensemble / statistiques générales (remplace statistiques())
    // ------------------------------------------------------------------
    #[Computed]
    public function statistiquesGlobales(): array
    {
        return [
            'investisseurs' => Investisseur::count(),
            'capital' => Versement::sum('montant'),
            'versements' => Versement::sum('montant'),
            'benefices' => Benefice::sum('montant'),
            'benefices_payes' => PaiementBenefice::sum('montant'),
            'retraits' => RetraitCapital::sum('montant'),
        ];
    }

    // ------------------------------------------------------------------
    // Situation globale d'un investisseur (remplace situationInvestisseur())
    // ------------------------------------------------------------------
    #[Computed]
    public function investisseurSelectionne(): ?Investisseur
    {
        if (! $this->investisseurId) {
            return null;
        }

        return Investisseur::with([
            'investissements.versements',
            'investissements.retraitsCapital',
            'investissements.repartitions',
        ])->find($this->investisseurId);
    }

    #[Computed]
    public function situationCapital(): float
    {
        if (! $this->investisseurSelectionne) {
            return 0;
        }

        return $this->investisseurSelectionne
            ->investissements()
            ->sum('montant');
    }

    #[Computed]
    public function situationVersements()
    {
        if (! $this->investisseurSelectionne) {
            return collect();
        }

        return $this->investisseurSelectionne
            ->investissements()
            ->withSum('versements', 'montant')
            ->get();
    }

    #[Computed]
    public function situationBenefices(): float
    {
        if (! $this->investisseurId) {
            return 0;
        }

        return Repartition::whereHas('investissement', function ($q) {
            $q->where('investisseur_id', $this->investisseurId);
        })->sum('montant');
    }

    public function render()
    {
        return view('livewire.investissements.rapport-manager', [
            'investisseurs' => $this->onglet === 'investisseurs' ? $this->donneesInvestisseurs() : null,
            'investissements' => $this->onglet === 'investissements' ? $this->donneesInvestissements() : null,
            'totalInvestissements' => $this->onglet === 'investissements' ? $this->totalInvestissements() : null,
            'versements' => $this->onglet === 'versements' ? $this->donneesVersements() : null,
            'totalVersements' => $this->onglet === 'versements' ? $this->totalVersements() : null,
            'benefices' => $this->onglet === 'benefices' ? $this->donneesBenefices() : null,
            'totalBenefices' => $this->onglet === 'benefices' ? $this->totalBenefices() : null,
            'totalDistribue' => $this->onglet === 'benefices' ? Repartition::sum('montant') : null,
            'totalPaye' => $this->onglet === 'benefices' ? PaiementBenefice::sum('montant') : null,
            'retraits' => $this->onglet === 'retraits' ? $this->donneesRetraits() : null,
            'totalRetraits' => $this->onglet === 'retraits' ? $this->totalRetraits() : null,
        ]);
    }

    // ------------------------------------------------------------------
    // Requêtes par onglet
    // ------------------------------------------------------------------
    protected function donneesInvestisseurs()
    {
        return Investisseur::withCount('investissements')
            ->withSum('investissements', 'montant')
            ->orderBy('nom')
            ->paginate(20);
    }

    protected function requeteInvestissements()
    {
        return Investissement::with(['investisseur'])
            ->when($this->dateDebut, fn ($q) => $q->whereDate('date_investissement', '>=', $this->dateDebut))
            ->when($this->dateFin, fn ($q) => $q->whereDate('date_investissement', '<=', $this->dateFin));
    }

    protected function donneesInvestissements()
    {
        return $this->requeteInvestissements()
            ->orderByDesc('date_investissement')
            ->paginate(30);
    }

    protected function totalInvestissements()
    {
        return $this->requeteInvestissements()->sum('montant');
    }

    protected function requeteVersements()
    {
        return Versement::with(['investissement.investisseur'])
            ->when($this->dateDebut, fn ($q) => $q->whereDate('date_versement', '>=', $this->dateDebut))
            ->when($this->dateFin, fn ($q) => $q->whereDate('date_versement', '<=', $this->dateFin));
    }

    protected function donneesVersements()
    {
        return $this->requeteVersements()
            ->orderByDesc('date_versement')
            ->paginate(30);
    }

    protected function totalVersements()
    {
        return $this->requeteVersements()->sum('montant');
    }

    protected function requeteBenefices()
    {
        return Benefice::query()
            ->when($this->dateDebut, fn ($q) => $q->whereDate('date_debut', '>=', $this->dateDebut))
            ->when($this->dateFin, fn ($q) => $q->whereDate('date_fin', '<=', $this->dateFin));
    }

    protected function donneesBenefices()
    {
        return $this->requeteBenefices()
            ->orderByDesc('date_debut')
            ->paginate(30);
    }

    protected function totalBenefices()
    {
        return $this->requeteBenefices()->sum('montant');
    }

    protected function requeteRetraits()
    {
        return RetraitCapital::with(['investissement.investisseur'])
            ->when($this->dateDebut, fn ($q) => $q->whereDate('date_retrait', '>=', $this->dateDebut))
            ->when($this->dateFin, fn ($q) => $q->whereDate('date_retrait', '<=', $this->dateFin));
    }

    protected function donneesRetraits()
    {
        return $this->requeteRetraits()
            ->orderByDesc('date_retrait')
            ->paginate(30);
    }

    protected function totalRetraits()
    {
        return $this->requeteRetraits()->sum('montant');
    }
}