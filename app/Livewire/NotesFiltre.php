<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Trimestre;
use App\Models\Matiere;
use App\Models\Note;

class NotesFiltre extends Component
{
    use WithPagination;

    // ── Filtres sélectionnés ──────────────────────────────────────────────
    public $annee_id    = null;
    public $trimestre_id = null;
    public $cycle_id    = null;
    public $classe_id   = null;
    public $matiere_id  = null;

    // ── Listes des selects ────────────────────────────────────────────────
    public $annees     = [];
    public $trimestres = [];
    public $cycles     = [];
    public $classes    = [];
    public $matieres   = [];

    // ── Réinitialise la pagination quand un filtre change ─────────────────
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->annees     = Annee::orderByDesc('id')->get();
        $this->cycles     = Cycle::orderBy('id')->get();
        $this->trimestres = Trimestre::orderBy('id')->get();
        $this->classes    = collect();
        $this->matieres   = collect();
    }

    // ── Quand Cycle change → recharge Classes ─────────────────────────────
    public function updatedCycleId($value)
    {
        $this->reset(['classe_id', 'matiere_id', 'matieres']);
        $this->resetPage();

        if (empty($value)) {
            $this->classes  = collect();
            $this->matieres = collect();
            return;
        }

        $this->classes = Classe::where('cycle_id', $value)
            ->orderBy('ordre')
            ->get();
    }

    // ── Quand Classe change → recharge Matières ───────────────────────────
    public function updatedClasseId($value)
    {
        $this->reset(['matiere_id']);
        $this->resetPage();

        if (empty($value)) {
            $this->matieres = collect();
            return;
        }

        $this->matieres = Matiere::whereHas('classes', function ($q) use ($value) {
            $q->where('classe_id', $value)
              ->where('active', true);
        })->orderBy('nom')->get();
    }

    // ── Réinitialise pagination sur tout changement de filtre ─────────────
    public function updatedAnneeId()    { $this->resetPage(); }
    public function updatedTrimestreId() { $this->resetPage(); }
    public function updatedMatiereId()  { $this->resetPage(); }

    // ── Reset complet ─────────────────────────────────────────────────────
    public function resetFiltres()
    {
        $this->reset([
            'annee_id', 'trimestre_id', 'cycle_id',
            'classe_id', 'matiere_id',
        ]);
        $this->classes  = collect();
        $this->matieres = collect();
        $this->resetPage();
    }

    // ── Query principale ──────────────────────────────────────────────────
    public function render()
    {
        $query = Note::with([
            'inscription.eleve',
            'inscription.annee',
            'inscription.classe',
            'matiere',
            'classe',
            'trimestre',
            'annee',
        ])->latest();

        if ($this->annee_id) {
            $query->where('annee_id', $this->annee_id);
        }

        if ($this->trimestre_id) {
            $query->where('trimestre_id', $this->trimestre_id);
        }

        if ($this->cycle_id) {
            $query->whereHas('classe', fn($q) =>
                $q->where('cycle_id', $this->cycle_id)
            );
        }

        if ($this->classe_id) {
            $query->where('classe_id', $this->classe_id);
        }

        if ($this->matiere_id) {
            $query->where('matiere_id', $this->matiere_id);
        }

        $notes = $query->paginate(250000);

        return view('livewire.notes-filtre', compact('notes'));
    }
}