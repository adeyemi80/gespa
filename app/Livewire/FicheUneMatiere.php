<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Inscription;
use App\Models\Note;
use Barryvdh\DomPDF\Facade\Pdf;

class FicheUneMatiere extends Component
{
    // ── Filtres ──────────────────────────────────────────────
    public $annee_id     = '';
    public $trimestre_id = '';
    public $classe_id    = '';
    public $matiere_id   = '';

    // ── Listes pour les selects ───────────────────────────────
    public $annees     = [];
    public $trimestres = [];
    public $classes    = [];
    public $matieres   = [];

    // ── Résultats ─────────────────────────────────────────────
    public $resultats  = [];
    public $classement = [];
    public $coef       = null;

    // ── Objets courants ───────────────────────────────────────
    public $annee     = null;
    public $trimestre = null;
    public $classe    = null;
    public $matiere   = null;

    // ─────────────────────────────────────────────────────────
    public function mount()
    {
        $this->annees  = Annee::orderByDesc('id')->get();
        $this->classes = Classe::where('cycle_id', 3)
                               ->orderByNiveau()
                               ->get();
    }

    // ── Quand l'année change → recharger les trimestres ───────
    public function updatedAnneeId($value)
    {
        $this->trimestres   = $value
            ? Annee::find($value)?->trimestres ?? []
            : [];
        $this->trimestre_id = '';
        $this->resetResultats();
    }

    // ── Quand la classe change → recharger les matières ───────
    public function updatedClasseId($value)
    {
        $this->matieres   = $value
            ? Classe::find($value)?->matieres()->get() ?? []
            : [];
        $this->matiere_id = '';
        $this->resetResultats();
    }

    // ── Reset des résultats à chaque changement de filtre ─────
    public function updatedTrimestreId() { $this->resetResultats(); }
    public function updatedMatiereId()   { $this->resetResultats(); }

    private function resetResultats()
    {
        $this->resultats  = [];
        $this->classement = [];
        $this->coef       = null;
    }

    // ─────────────────────────────────────────────────────────
    // GÉNÉRER LA FICHE
    // ─────────────────────────────────────────────────────────
    public function generer()
    {
        $this->validate([
            'annee_id'     => 'required|exists:annees,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id'    => 'required|exists:classes,id',
            'matiere_id'   => 'required|exists:matieres,id',
        ]);

        $this->annee     = Annee::findOrFail($this->annee_id);
        $this->trimestre = Trimestre::findOrFail($this->trimestre_id);
        $this->classe    = Classe::findOrFail($this->classe_id);
        $this->matiere   = Matiere::findOrFail($this->matiere_id);
        $this->coef      = $this->matiere->coefficient;

        // ── Élèves inscrits triés alphabétiquement ────────────
        $eleves = Inscription::where('inscriptions.annee_id', $this->annee_id)
            ->where('inscriptions.classe_id', $this->classe_id)
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->orderBy('eleves.nom')
            ->orderBy('eleves.prenom')
            ->select('eleves.*')
            ->get();

        // ── Notes de la matière pour ce trimestre ─────────────
        $notes = Note::where([
            'annee_id'     => $this->annee_id,
            'trimestre_id' => $this->trimestre_id,
            'matiere_id'   => $this->matiere_id,
        ])->get()->keyBy('eleve_id');

        // ── Construction des résultats ────────────────────────
        $resultats = [];
        foreach ($eleves as $eleve) {
            $note = $notes[$eleve->id] ?? null;
            $moy  = $note
                ? collect([$note->devoir, $note->mcc, $note->composition])
                    ->filter()
                    ->avg()
                : null;

            $resultats[] = [
                'eleve'    => $eleve,
                'note'     => $note,
                'moy_epe'  => $note?->epe ?? null,
                'moyenne'  => $moy,
                'moy_coef' => $moy ? round($moy * $this->coef, 2) : null,
                'rang'     => '',
            ];
        }

        // ── Classement (uniquement les élèves avec moyenne) ───
        $classement = collect($resultats)
            ->whereNotNull('moyenne')
            ->sortByDesc('moyenne')
            ->values();

        foreach ($classement as $i => $item) {
            $classement[$i]['rang'] = $i + 1;
        }

        // ── Fusionner le rang dans $resultats ─────────────────
        $rangs = $classement->keyBy(fn($r) => $r['eleve']->id);
        foreach ($resultats as &$res) {
            $res['rang'] = $rangs[$res['eleve']->id]['rang'] ?? '';
        }

        $this->resultats  = $resultats;
        $this->classement = $classement->toArray();
    }

    // ─────────────────────────────────────────────────────────
    // EXPORT PDF (via route GET — voir FicheUneMatierePdfController)
    // ─────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.fiche-une-matiere');
    }
}