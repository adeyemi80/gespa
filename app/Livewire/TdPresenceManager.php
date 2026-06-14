<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\TdSeance;
use App\Models\TdPresence;
use App\Models\Inscription;

class TdPresenceManager extends Component
{
    public $annee_id;
    public $cycle_id;
    public $classe_id;
    public $seance_id;

    public $annees    = [];
    public $cycles    = [];
    public $classes   = [];
    public $seances   = [];
    public $eleves    = [];
    public $presences = [];

    public $tousSelectionnes = false;

    public function mount()
    {
        $this->annees   = Annee::orderByDesc('id')->get();
        $this->annee_id = Annee::where('en_cours', true)->first()?->id
                          ?? $this->annees->first()?->id;

        $this->cycles = Cycle::orderBy('id')->get();
    }

    public function updatedAnneeId()
    {
        $this->reset(['cycle_id', 'classe_id', 'seance_id', 'classes', 'seances', 'eleves', 'presences', 'tousSelectionnes']);
    }

    public function updatedCycleId()
    {
        $this->reset(['classe_id', 'seance_id', 'seances', 'eleves', 'presences', 'tousSelectionnes']);
        $this->chargerClasses();
    }

    public function updatedClasseId()
    {
        $this->reset(['seance_id', 'eleves', 'presences', 'tousSelectionnes']);
        $this->chargerSeances();
    }

    public function updatedSeanceId()
    {
        $this->tousSelectionnes = false;
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

    private function chargerSeances()
    {
        if (!$this->classe_id || !$this->annee_id) {
            $this->seances = [];
            return;
        }

        $this->seances = TdSeance::where('annee_id', $this->annee_id)
            ->where('classe_id', $this->classe_id)
            ->orderByDesc('date')
            ->get();

        $this->seance_id = $this->seances->first()?->id;

        if ($this->seance_id) {
            $this->chargerEleves();
        }
    }

    private function chargerEleves()
    {
        if (!$this->seance_id || !$this->annee_id || !$this->classe_id) {
            $this->eleves    = [];
            $this->presences = [];
            return;
        }

        $this->eleves = Inscription::with('eleve')
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->where('inscriptions.annee_id', $this->annee_id)
            ->where('inscriptions.classe_id', $this->classe_id)
            ->orderBy('eleves.nom')
            ->orderBy('eleves.prenom')
            ->select('inscriptions.*', 'eleves.nom', 'eleves.prenom')
            ->get();

        $existantes = TdPresence::where('td_seance_id', $this->seance_id)
            ->pluck('present', 'eleve_id');

        $this->presences = [];
        foreach ($this->eleves as $insc) {
            $this->presences[$insc->eleve_id] = (bool) ($existantes[$insc->eleve_id] ?? false);
        }

        $this->tousSelectionnes = count($this->presences) > 0
            && collect($this->presences)->every(fn($p) => $p === true);
    }

    public function toggleTous()
    {
        $this->tousSelectionnes = !$this->tousSelectionnes;

        foreach ($this->presences as $eleveId => $val) {
            $this->presences[$eleveId] = $this->tousSelectionnes;
        }
    }

    public function save()
{
    if (!$this->seance_id) return;

    foreach ($this->presences as $eleveId => $present) {
        TdPresence::updateOrCreate(
            [
                'td_seance_id' => $this->seance_id,
                'eleve_id'     => $eleveId,
            ],
            [
                'present' => (bool) $present,
            ]
        );
    }

    // ✅ On vide tout après enregistrement
    $this->reset(['eleves', 'presences', 'seance_id', 'tousSelectionnes']);

    session()->flash('success', 'Présences enregistrées.');
}

// ✅ Nouvelle méthode pour consulter les présences en BDD
public function voirPresences()
{
    return redirect()->route('td-presences.show', $this->seance_id);
}
    public function render()
    {
        return view('livewire.td-presence-manager');
    }
}