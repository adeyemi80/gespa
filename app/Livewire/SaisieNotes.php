<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;
use App\Models\Inscription;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Trimestre;
use App\Models\Annee;
use App\Traits\CalculeMoyennes;

class SaisieNotes extends Component
{
    use CalculeMoyennes;

    public $annee_id     = null;
    public $classe_id    = null;
    public $matiere_id   = null;
    public $trimestre_id = null;

    public $matieres  = [];
    public $trimestres = [];

    public array $notes = [];
    public bool $sauvegarde = false;

    public function mount(): void
    {
        $annee = Annee::where('en_cours', true)->first();
        if ($annee) {
            $this->annee_id   = $annee->id;
            $this->trimestres = $annee->trimestres;
        }
        $this->matieres = collect();
    }

    public function updatedClasseId($value): void
    {
        $this->matieres   = $value
            ? Classe::with('matieres')->find($value)?->matieres ?? collect()
            : collect();
        $this->matiere_id = null;
        $this->notes      = [];
    }

    public function updatedAnneeId($value): void
    {
        $annee              = Annee::with('trimestres')->find($value);
        $this->trimestres   = $annee ? $annee->trimestres : collect();
        $this->trimestre_id = null;
        $this->classe_id    = null;
        $this->notes        = [];
    }

    public function chargerNotes(): void
    {
        $this->validate([
            'annee_id'    => 'required|exists:annees,id',
            'classe_id'   => 'required|exists:classes,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'trimestre_id'=> 'required|exists:trimestres,id',
        ]);

        $inscriptions = Inscription::with('eleve')
            ->where('classe_id', $this->classe_id)
            ->where('annee_id', $this->annee_id)
            ->get();

        $this->notes = [];

        foreach ($inscriptions as $inscription) {
            $note = Note::firstOrNew([
                'inscription_id' => $inscription->id,
                'matiere_id'     => $this->matiere_id,
                'trimestre_id'   => $this->trimestre_id,
                'annee_id'       => $this->annee_id,
                'classe_id'      => $this->classe_id,
            ]);

            $this->notes[$inscription->id] = [
                'eleve'           => $inscription->eleve->nom . ' ' . $inscription->eleve->prenom,
                'matricule'       => $inscription->eleve->matricule,
                'interrogation1'  => $note->interrogation1,
                'interrogation2'  => $note->interrogation2,
                'interrogation3'  => $note->interrogation3,
                'devoir1'         => $note->devoir1,
                'devoir2'         => $note->devoir2,
                'moyenne_interro' => $note->moyenne_interro,
                'moyenne_matiere' => $note->moyenne_matiere,
            ];
        }

        $this->sauvegarde = false;
    }

    public function updatedNotes($value, $key): void
    {
        [$inscriptionId] = explode('.', $key);

        $n = $this->notes[$inscriptionId] ?? [];

        $i1 = is_numeric($n['interrogation1'] ?? null) ? (float)$n['interrogation1'] : null;
        $i2 = is_numeric($n['interrogation2'] ?? null) ? (float)$n['interrogation2'] : null;
        $i3 = is_numeric($n['interrogation3'] ?? null) ? (float)$n['interrogation3'] : null;
        $d1 = is_numeric($n['devoir1']        ?? null) ? (float)$n['devoir1']        : null;
        $d2 = is_numeric($n['devoir2']        ?? null) ? (float)$n['devoir2']        : null;

        $moyInterro = $this->calculerMoyenneInterro($i1, $i2, $i3);
        $moyMatiere = $this->calculerMoyenneMatiere($moyInterro, $d1, $d2);

        $this->notes[$inscriptionId]['moyenne_interro'] = $moyInterro;
        $this->notes[$inscriptionId]['moyenne_matiere'] = $moyMatiere;
    }

    public function sauvegarder(): void
    {
        foreach ($this->notes as $inscriptionId => $data) {
            $note = Note::firstOrNew([
                'inscription_id' => $inscriptionId,
                'matiere_id'     => $this->matiere_id,
                'trimestre_id'   => $this->trimestre_id,
                'annee_id'       => $this->annee_id,
                'classe_id'      => $this->classe_id,
            ]);

            $note->interrogation1  = is_numeric($data['interrogation1'] ?? null) ? (float)$data['interrogation1'] : null;
            $note->interrogation2  = is_numeric($data['interrogation2'] ?? null) ? (float)$data['interrogation2'] : null;
            $note->interrogation3  = is_numeric($data['interrogation3'] ?? null) ? (float)$data['interrogation3'] : null;
            $note->devoir1         = is_numeric($data['devoir1']        ?? null) ? (float)$data['devoir1']        : null;
            $note->devoir2         = is_numeric($data['devoir2']        ?? null) ? (float)$data['devoir2']        : null;
            $note->moyenne_interro = $data['moyenne_interro'] ?? null;
            $note->moyenne_matiere = $data['moyenne_matiere'] ?? null;

            $note->save();
        }

        $this->sauvegarde = true;
        session()->flash('success', 'Notes sauvegardées avec succès.');
    }

    public function render()
    {
        return view('livewire.saisie-notes', [
            'annees'  => Annee::orderBy('nom')->get(),
            'classes' => Classe::where('cycle_id', 3)
                               ->orderBy('id')
                               ->get(),
        ]);
    }
}