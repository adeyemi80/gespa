<?php

namespace App\Livewire\Paiements;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaiementIndex extends Component
{
    use WithPagination;

    public mixed $annee_id       = null;
    public mixed $cycle_id       = null;
    public mixed $classe_id      = null;
    public mixed $inscription_id = null;
    public mixed $frais_id       = null;
    public ?string $date_debut   = null;
    public ?string $date_fin     = null;

    // ── Helper cast ──────────────────────────────────────────────
    private function intOrNull(mixed $value): ?int
    {
        $cast = (int) $value;
        return $cast > 0 ? $cast : null;
    }

    // ── Hooks updated ────────────────────────────────────────────
    public function updatedAnneeId(): void
    {
        $this->annee_id       = $this->intOrNull($this->annee_id);
        $this->cycle_id       = null;
        $this->classe_id      = null;
        $this->inscription_id = null;
        $this->frais_id       = null;
        $this->resetPage();
    }

    public function updatedCycleId(): void
    {
        $this->cycle_id       = $this->intOrNull($this->cycle_id);
        $this->classe_id      = null;
        $this->inscription_id = null;
        $this->frais_id       = null;
        $this->resetPage();
    }

    public function updatedClasseId(): void
    {
        $this->classe_id      = $this->intOrNull($this->classe_id);
        $this->inscription_id = null;
        $this->frais_id       = null;
        $this->resetPage();
    }

    public function updatedInscriptionId(): void
    {
        $this->inscription_id = $this->intOrNull($this->inscription_id);
        $this->resetPage();
    }

    public function updatedFraisId(): void
    {
        $this->frais_id = $this->intOrNull($this->frais_id);
        $this->resetPage();
    }

    public function updatedDateDebut(): void { $this->resetPage(); }
    public function updatedDateFin(): void   { $this->resetPage(); }

    public function resetFiltres(): void
    {
        $this->reset([
            'annee_id', 'cycle_id', 'classe_id',
            'inscription_id', 'frais_id', 'date_debut', 'date_fin',
        ]);
        $this->resetPage();
    }

    // ── Export PDF ───────────────────────────────────────────────
    public function exportPdf(): StreamedResponse
    {
        $anneeId       = $this->intOrNull($this->annee_id);
        $cycleId       = $this->intOrNull($this->cycle_id);
        $classeId      = $this->intOrNull($this->classe_id);
        $inscriptionId = $this->intOrNull($this->inscription_id);
        $fraisId       = $this->intOrNull($this->frais_id);

        $query = Paiement::with([
            'inscription.eleve',
            'inscription.classe',
            'inscription.annee',
            'frais',
        ]);

        if ($anneeId) {
            $query->whereHas('inscription', fn($q) =>
                $q->where('annee_id', $anneeId)
            );
        }
        if ($cycleId) {
            $query->whereHas('inscription.classe', fn($q) =>
                $q->where('cycle_id', $cycleId)
            );
        }
        if ($classeId) {
            $query->whereHas('inscription', fn($q) =>
                $q->where('classe_id', $classeId)
            );
        }
        if ($inscriptionId) {
            $query->where('inscription_id', $inscriptionId);
        }
        if ($fraisId) {
            $query->where('frais_id', $fraisId);
        }
        if ($this->date_debut) {
            $query->whereDate('date_paiement', '>=', $this->date_debut);
        }
        if ($this->date_fin) {
            $query->whereDate('date_paiement', '<=', $this->date_fin);
        }

        $paiements = $query->orderBy('date_paiement')->get();
        $total     = $paiements->sum('montant_verse');

        $pdf = Pdf::loadView('paiements.export-pdf', [
            'paiements' => $paiements,
            'total'     => $total,
            'filters'   => [
                'annee_id'       => $anneeId,
                'cycle_id'       => $cycleId,
                'classe_id'      => $classeId,
                'inscription_id' => $inscriptionId,
                'frais_id'       => $fraisId,
                'date_debut'     => $this->date_debut,
                'date_fin'       => $this->date_fin,
            ],
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'liste_paiements.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    // ── Render ───────────────────────────────────────────────────
    public function render()
    {
        $anneeId       = $this->intOrNull($this->annee_id);
        $cycleId       = $this->intOrNull($this->cycle_id);
        $classeId      = $this->intOrNull($this->classe_id);
        $inscriptionId = $this->intOrNull($this->inscription_id);
        $fraisId       = $this->intOrNull($this->frais_id);

        // ── ANNÉES ──────────────────────────────────────────────
        $annees = Annee::orderBy('nom')->get();

        // ── CYCLES (filtrés par année) ───────────────────────────
        $cyclesQuery = Cycle::query();
        if ($anneeId) {
            $cyclesQuery->whereHas('classes.inscriptions', fn($q) =>
                $q->where('annee_id', $anneeId)
            );
        }
        $cycles = $cyclesQuery->orderBy('nom')->get();

        // ── CLASSES (filtrées par année + cycle) ─────────────────
        $classesQuery = Classe::query();
        if ($anneeId) {
            $classesQuery->whereHas('inscriptions', fn($q) =>
                $q->where('annee_id', $anneeId)
            );
        }
        if ($cycleId) {
            $classesQuery->where('cycle_id', $cycleId);
        }
        $classes = $classesQuery->orderBy('nom')->get();

        // ── FRAIS (selon classe) ─────────────────────────────────
        $frais = collect();
        if ($classeId) {
            $frais = Frais::whereHas('paiements.inscription', fn($q) =>
                $q->where('classe_id', $classeId)
            )->orderBy('description')->get();
        }

        // ── INSCRIPTIONS/ÉLÈVES (année + classe) ─────────────────
        $inscriptions = collect();
        if ($anneeId && $classeId) {
            $inscriptions = Inscription::with(['eleve:id,nom,prenom'])
                ->where('annee_id', $anneeId)
                ->where('classe_id', $classeId)
                ->orderBy('eleve_id')
                ->get();
        }

        // ── PAIEMENTS (paginés + tous filtres) ───────────────────
        $query = Paiement::with([
            'inscription.eleve:id,nom,prenom',
            'inscription.classe:id,nom',
            'inscription.annee:id,nom',
            'frais:id,description',
        ]);

        if ($anneeId) {
            $query->whereHas('inscription', fn($q) =>
                $q->where('annee_id', $anneeId)
            );
        }
        if ($cycleId) {
            $query->whereHas('inscription.classe', fn($q) =>
                $q->where('cycle_id', $cycleId)
            );
        }
        if ($classeId) {
            $query->whereHas('inscription', fn($q) =>
                $q->where('classe_id', $classeId)
            );
        }
        if ($inscriptionId) {
            $query->where('inscription_id', $inscriptionId);
        }
        if ($fraisId) {
            $query->where('frais_id', $fraisId);
        }
        if ($this->date_debut) {
            $query->whereDate('date_paiement', '>=', $this->date_debut);
        }
        if ($this->date_fin) {
            $query->whereDate('date_paiement', '<=', $this->date_fin);
        }

        $paiements = $query->orderByDesc('id')->paginate(25);

        return view('livewire.paiements.paiement-index', compact(
            'annees', 'cycles', 'classes', 'frais', 'inscriptions', 'paiements'
        ));
    }
}