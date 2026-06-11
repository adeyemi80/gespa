<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Inscription;

class AnnulationPassage extends Component
{
    // Étape courante (1 à 4)
    public int $etape = 1;

    // Sélections
    public ?int $anneeId = null;
    public ?int $cycleId = null;
    public ?int $classeId = null;
    public array $elevesSelectionnes = [];
    public bool $tousSelectionnes = false;

    // Données chargées
    public $annees = [];
    public $cycles = [];
    public $classes = [];
    public $eleves = [];

    // Confirmation
    public bool $showConfirmation = false;

    // Messages
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    // ─────────────────────────────────────────────
    // Lifecycle
    // ─────────────────────────────────────────────

    public function mount()
    {
        $this->annees = Annee::orderByDesc('nom')->get();
    }

    // ─────────────────────────────────────────────
    // Watchers
    // ─────────────────────────────────────────────

    public function updatedAnneeId()
    {
        $this->reset(['cycleId', 'classeId', 'classes', 'eleves', 'elevesSelectionnes', 'tousSelectionnes']);
        $this->etape = 2;
        $this->cycles = Cycle::orderBy('libelle')->get();
    }

    public function updatedCycleId()
    {
        $this->reset(['classeId', 'eleves', 'elevesSelectionnes', 'tousSelectionnes']);
        $this->classes = Classe::where('cycle_id', $this->cycleId)->orderBy('libelle')->get();
    }

    public function updatedClasseId()
    {
        $this->reset(['eleves', 'elevesSelectionnes', 'tousSelectionnes']);
        $this->chargerEleves();
        $this->etape = 3;
    }

    public function updatedTousSelectionnes($value)
    {
        if ($value) {
            $this->elevesSelectionnes = $this->eleves->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->elevesSelectionnes = [];
        }
    }

    public function updatedElevesSelectionnes()
    {
        $this->tousSelectionnes = count($this->elevesSelectionnes) === $this->eleves->count();
    }

    // ─────────────────────────────────────────────
    // Actions
    // ─────────────────────────────────────────────

    private function chargerEleves()
    {
        $this->eleves = Eleve::whereHas('inscriptions', function ($q) {
            $q->where('annee_id', $this->anneeId)
              ->where('classe_id', $this->classeId);
        })
        ->with(['inscriptions' => fn($q) => $q->where('annee_id', $this->anneeId)])
        ->orderBy('nom')
        ->get();
    }

    public function passerEtape4()
    {
        if (empty($this->elevesSelectionnes)) {
            $this->errorMessage = 'Veuillez sélectionner au moins un élève.';
            return;
        }
        $this->errorMessage = null;
        $this->etape = 4;
        $this->showConfirmation = true;
    }

    public function confirmerAnnulation()
    {
        try {
            Inscription::whereIn('eleve_id', $this->elevesSelectionnes)
                ->where('annee_id', $this->anneeId)
                ->where('classe_id', $this->classeId)
                ->delete();

            $nb = count($this->elevesSelectionnes);
            $this->successMessage = "{$nb} inscription(s) supprimée(s) avec succès.";
            $this->reset([
                'etape', 'anneeId', 'cycleId', 'classeId',
                'eleves', 'elevesSelectionnes', 'tousSelectionnes', 'showConfirmation'
            ]);
            $this->etape = 1;
            $this->cycles = [];
            $this->classes = [];
        } catch (\Exception $e) {
            $this->errorMessage = 'Une erreur est survenue : ' . $e->getMessage();
        }
    }

    public function annulerConfirmation()
    {
        $this->showConfirmation = false;
        $this->etape = 3;
    }

    public function reinitialiser()
    {
        $this->reset();
        $this->annees = Annee::orderByDesc('nom')->get();
        $this->etape = 1;
    }

    // ─────────────────────────────────────────────
    // Computed helpers
    // ─────────────────────────────────────────────

    public function getElevesSelectionnesDetailsProperty()
    {
        if (!$this->eleves || empty($this->elevesSelectionnes)) return collect();
        return $this->eleves->whereIn('id', $this->elevesSelectionnes);
    }

    public function getAnneeLibelleProperty(): string
    {
        return $this->annees->firstWhere('id', $this->anneeId)?->nom ?? '';
    }

    public function getClasseLibelleProperty(): string
    {
        return $this->classes->firstWhere('id', $this->classeId)?->libelle ?? '';
    }

    // ─────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────

    public function render()
    {
        return view('livewire.annulation-passage')
            ->layout('layouts.app');
    }
}