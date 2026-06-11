<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Inscription;
use App\Models\Note;
use PDF;

class FicheNote extends Component
{
    // 🔹 Champs sélectionnés
    public $annee_id;
    public $trimestre_id;
    public $classe_id;
    public $matiere_id;

    // 🔹 Collections pour les selects
    public $annees = [];
    public $trimestres = [];
    public $classes = [];
    public $matieres = [];

    // 🔹 Objets sélectionnés
    public $annee;
    public $trimestre;
    public $classe;
    public $matiere;
    public $coef;

    // 🔹 Résultats
    public $resultats = [];
    public $classement = [];

    // 🔹 Initialisation
public function mount()
{
    $this->annees = Annee::with('classes.matieres', 'trimestres')
                         ->orderByDesc('id')
                         ->get();

    // Pré-remplir la première année
    if ($this->annees->isNotEmpty()) {
        $this->annee_id = $this->annees->first()->id;
        $this->updatedAnneeId($this->annee_id);
    }

    // Si une classe est déjà définie, charger ses matières
    if ($this->classes->isNotEmpty()) {
        $this->classe_id = $this->classes->first()->id;
        $this->updatedClasseId($this->classe_id);
    }
}

public function updatedClasseId($value)
{
    $classe = Classe::with('matieres')->find($value);
    $this->matieres = $classe?->matieres ?? collect();

    // Réinitialiser la sélection de matière
    $this->matiere_id = $this->matieres->isNotEmpty() ? $this->matieres->first()->id : null;
}
    // 🔁 Quand l'année change, mettre à jour classes et trimestres
   public function updatedAnneeId($value)
{
    $annee = Annee::with('classes.matieres', 'trimestres')->find($value);

    $this->classes    = $annee?->classes ?? collect();
    $this->trimestres = $annee?->trimestres ?? collect();

    // Réinitialiser les selects dépendants
    $this->reset(['trimestre_id', 'classe_id', 'matiere_id', 'matieres']);
}


    // 🔹 Génération des résultats (équivalent de generer() du controller)
    public function generer()
    {
        $this->validate([
            'annee_id'     => 'required',
            'trimestre_id' => 'required',
            'classe_id'    => 'required',
            'matiere_id'   => 'required',
        ]);

        $this->annee     = Annee::findOrFail($this->annee_id);
        $this->trimestre = $this->annee->trimestres()->findOrFail($this->trimestre_id);
        $this->classe    = $this->annee->classes()->findOrFail($this->classe_id);
        $this->matiere   = $this->classe->matieres()->findOrFail($this->matiere_id);

        $this->coef = $this->matiere->pivot->coef;

        // 🔹 Récupération des élèves de la classe pour l'année
        $eleves = Inscription::where('inscriptions.annee_id', $this->annee->id)
            ->where('inscriptions.classe_id', $this->classe->id)
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->orderBy('eleves.nom')
            ->orderBy('eleves.prenom')
            ->select('eleves.*')
            ->get();

        // 🔹 Notes de la matière et trimestre
        $notes = Note::where([
            'annee_id'     => $this->annee->id,
            'trimestre_id' => $this->trimestre->id,
            'matiere_id'   => $this->matiere->id,
        ])->get()->keyBy('eleve_id');

        $resultats = [];

        foreach ($eleves as $eleve) {
            $n = $notes[$eleve->id] ?? null;

            $moy = $n
                ? collect([$n->devoir, $n->mcc, $n->composition])->filter()->avg()
                : null;

            $resultats[] = [
                'eleve'    => $eleve,
                'note'     => $n,
                'moy_epe'  => $n?->epe ?? null,
                'moyenne'  => $moy,
                'moy_coef' => $moy ? $moy * $this->coef : null,
            ];
        }

        // 🔹 Classement
        $classement = collect($resultats)
            ->whereNotNull('moyenne')
            ->sortByDesc('moyenne')
            ->values();

        foreach ($classement as $i => $res) {
            $classement[$i]['rang'] = $i + 1;
        }

        $this->resultats  = $resultats;
        $this->classement = $classement;
    }

    // 🔹 Rendu de la vue
    public function render()
    {
        return view('livewire.fiche-note', [
            'annees'     => $this->annees,
            'trimestres' => $this->trimestres,
            'classes'    => $this->classes,
            'matieres'   => $this->matieres,
            'resultats'  => $this->resultats,
            'classement' => $this->classement,
            'annee'      => $this->annee,
            'trimestre'  => $this->trimestre,
            'classe'     => $this->classe,
            'matiere'    => $this->matiere,
            'coef'       => $this->coef,
        ]);
    }

    // 🔹 Export PDF via route existante
    public function exportPdf()
    {
        return redirect()->route('fiches.pdf', [
            'annee_id'     => $this->annee_id,
            'trimestre_id' => $this->trimestre_id,
            'classe_id'    => $this->classe_id,
            'matiere_id'   => $this->matiere_id,
        ]);
    }
    
}