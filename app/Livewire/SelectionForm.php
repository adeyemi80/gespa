<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Trimestre;
use App\Models\Matiere;

class SelectionForm extends Component
{
    // 🔹 Données pour les selects
    public $annees = [];
    public $classes = [];
    public $trimestres = [];
    public $matieres = [];

    // 🔹 Valeurs sélectionnées
    public $annee_id;
    public $classe_id;
    public $trimestre_id;
    public $matiere_id;

    // 🔹 Route vers laquelle envoyer le formulaire
    public $actionRoute;

    // 🔹 Texte du bouton
    public $btnText = 'Générer';

    /**
     * Initialisation
     */
    public function mount($actionRoute = null, $btnText = null)
    {
        $this->annees = Annee::orderByDesc('id')->get();
        $this->actionRoute = $actionRoute ?? route('fiches.generer');
        if ($btnText) $this->btnText = $btnText;
    }

    /**
     * Quand année change
     */
    public function updatedAnneeId($value)
    {
        $annee = Annee::with('classes', 'trimestres')->find($value);
        $this->classes = $annee?->classes ?? [];
        $this->trimestres = $annee?->trimestres ?? [];

        // reset
        $this->classe_id = null;
        $this->trimestre_id = null;
        $this->matiere_id = null;
        $this->matieres = [];
    }

    /**
     * Quand classe change
     */
    public function updatedClasseId($value)
    {
        $classe = Classe::with('matieres')->find($value);
        $this->matieres = $classe?->matieres ?? [];

        $this->matiere_id = null;
    }

    public function render()
    {
        return view('livewire.selection-form');
    }
}