<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InscriptionFrais;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Eleve;

class InscriptionFraisTable extends Component
{
    use WithPagination;

    public $annee_id = '';
    public $classe_id = '';
    public $eleve_id = '';

    protected $queryString = [
        'annee_id'  => ['except' => ''],
        'classe_id' => ['except' => ''],
        'eleve_id'  => ['except' => ''],
    ];

    public function updatedClasseId()
    {
        // Réinitialise l’élève si on change la classe
        $this->eleve_id = '';
    }

    public function render()
    {
        $query = InscriptionFrais::with([
            'inscription.eleve',
            'inscription.classe',
            'frais'
        ]);

        if ($this->annee_id) {
            $query->where('annee_id', $this->annee_id);
        }

        if ($this->classe_id) {
            $query->whereHas('inscription', fn($q) => $q->where('classe_id', $this->classe_id));
        }

        if ($this->eleve_id) {
            $query->whereHas('inscription', fn($q) => $q->where('eleve_id', $this->eleve_id));
        }

        $inscriptionFrais = $query->latest()->paginate(50);

        // Élèves filtrés par classe (ou tous si pas de classe)
        $eleves = $this->classe_id
            ? Eleve::whereHas('inscriptions', fn($q) => $q->where('classe_id', $this->classe_id))
                   ->orderBy('nom')
                   ->get()
            : Eleve::orderBy('nom')->get();

        return view('livewire.inscription-frais-table', [
            'inscriptionFrais' => $inscriptionFrais,
            'classes'          => Classe::orderByNiveau('nom')->get(),
            'annees'           => Annee::orderBy('nom')->get(),
            'eleves'           => $eleves,
        ])->layout('tableau.neutre');
    }
}