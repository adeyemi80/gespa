<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\TdPaiement;

class TdPaiementManager extends Component
{
    public $annee_id;
    public $cycle_id;
    public $classe_id;
    public $eleve_id;

    public $montant;
    public $date_paiement;

    public $annees  = [];
    public $cycles  = [];
    public $classes = [];
    public $eleves  = [];
    public $paiements = [];

    public function mount()
    {
        $this->annees   = Annee::orderByDesc('id')->get();
        $this->annee_id = Annee::where('en_cours', true)->first()?->id
                          ?? $this->annees->first()?->id;

        $this->cycles   = Cycle::orderBy('id')->get();

        $this->date_paiement = now()->format('Y-m-d');

        $this->chargerPaiements();
    }

    public function updatedAnneeId()
    {
        $this->classes = [];
        $this->eleves  = [];
        $this->classe_id = null;
        $this->eleve_id   = null;

        if ($this->cycle_id) {
            $this->chargerClasses();
        }

        $this->chargerPaiements();
    }

    public function updatedCycleId()
    {
        $this->eleves    = [];
        $this->classe_id = null;
        $this->eleve_id  = null;

        $this->chargerClasses();
    }

    public function updatedClasseId()
    {
        $this->eleve_id = null;
        $this->chargerEleves();
    }

    private function chargerClasses()
    {
        if (!$this->cycle_id) {
            $this->classes = [];
            return;
        }

        $this->classes = Classe::where('cycle_id', $this->cycle_id)
            ->orderByNiveau()
            ->get();
    }

    private function chargerEleves()
    {
        if (!$this->classe_id || !$this->annee_id) {
            $this->eleves = [];
            return;
        }

        $this->eleves = Inscription::with('eleve')
            ->where('inscriptions.annee_id', $this->annee_id)
            ->where('inscriptions.classe_id', $this->classe_id)
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->orderBy('eleves.nom')
            ->orderBy('eleves.prenom')
            ->select('inscriptions.*', 'eleves.nom', 'eleves.prenom', 'eleves.id as eleve_id')
            ->get();
    }

    private function chargerPaiements()
    {
        $query = TdPaiement::with('eleve')
            ->where('annee_id', $this->annee_id)
            ->orderByDesc('date_paiement');

        if ($this->classe_id) {
            $eleveIds = Inscription::where('annee_id', $this->annee_id)
                ->where('classe_id', $this->classe_id)
                ->pluck('eleve_id');

            $query->whereIn('eleve_id', $eleveIds);
        }

        $this->paiements = $query->limit(50)->get();
    }

    public function save()
    {
        $this->validate([
            'annee_id'      => 'required|exists:annees,id',
            'eleve_id'      => 'required|exists:eleves,id',
            'montant'       => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
        ]);

        TdPaiement::create([
            'eleve_id'      => $this->eleve_id,
            'annee_id'      => $this->annee_id,
            'montant'       => $this->montant,
            'date_paiement' => $this->date_paiement,
        ]);

        $this->montant = null;
        $this->eleve_id = null;

        $this->chargerPaiements();

        session()->flash('message', 'Paiement enregistré.');
    }

    public function render()
    {
        return view('livewire.td-paiement-manager');
    }
}