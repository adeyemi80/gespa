<?php

namespace App\Livewire\Enseignements;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Enseignant;
use App\Models\Enseignement;
use App\Models\Matiere;
use Livewire\Component;
use Livewire\WithPagination;

class Gestion extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /*
    |--------------------------------------------------------------------------
    | FORMULAIRE
    |--------------------------------------------------------------------------
    */

    public $enseignement_id = null;

    public $annee_id = null;
    public $cycle_id = null;
    public $classe_id = null;
    public $matiere_id = null;
    public $enseignant_id = null;

    public $coefficient = 1;
    public $volume_horaire = null;
    public $heures_par_semaine = null;
    public $actif = true;

    /*
    |--------------------------------------------------------------------------
    | FILTRES
    |--------------------------------------------------------------------------
    */

    public $filtre_annee_id = null;
    public $filtre_cycle_id = null;
    public $filtre_classe_id = null;
    public $filtre_matiere_id = null;
    public $filtre_enseignant_id = null;
    public $filtre_actif = '';

    /*
    |--------------------------------------------------------------------------
    | RECHERCHE
    |--------------------------------------------------------------------------
    */

    public $search = '';

    /*
    |--------------------------------------------------------------------------
    | DONNÉES DÉPENDANTES
    |--------------------------------------------------------------------------
    */

    public $cycles = [];
    public $classes = [];
    public $matieres = [];

    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    public $showModal = false;

    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->cycles = Cycle::orderBy('nom')->get();

        $this->classes = [];
        $this->matieres = [];
    }

    /*
    |--------------------------------------------------------------------------
    | RÈGLES DE VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function rules()
    {
        return [

            'annee_id' => [
                'required',
                'integer',
                'exists:annees,id',
            ],

            'cycle_id' => [
                'required',
                'integer',
                'exists:cycles,id',
            ],

            'classe_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'matiere_id' => [
                'required',
                'integer',
                'exists:matieres,id',
            ],

            'enseignant_id' => [
                'required',
                'integer',
                'exists:enseignants,id',
            ],

            'coefficient' => [
                'required',
                'numeric',
                'min:0',
            ],

            'volume_horaire' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'heures_par_semaine' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'actif' => [
                'boolean',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ANNÉE → CYCLE → CLASSES
    |--------------------------------------------------------------------------
    */

    public function updatedAnneeId($value)
    {
        $this->cycle_id = null;
        $this->classe_id = null;
        $this->matiere_id = null;

        $this->classes = [];
        $this->matieres = [];

        /*
        |--------------------------------------------------------------------------
        | Tant qu'aucun cycle n'est choisi, aucune classe n'est affichée.
        |--------------------------------------------------------------------------
        */

        if (!$value) {
            return;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CYCLE → CLASSES
    |--------------------------------------------------------------------------
    */

    public function updatedCycleId($value)
    {
        $this->classe_id = null;
        $this->matiere_id = null;

        $this->classes = [];
        $this->matieres = [];

        if (!$value || !$this->annee_id) {
            return;
        }

        $this->chargerClasses();
    }

    /*
    |--------------------------------------------------------------------------
    | CLASSE → MATIÈRES
    |--------------------------------------------------------------------------
    */

    public function updatedClasseId($value)
    {
        $this->matiere_id = null;
        $this->matieres = [];

        /*
        |--------------------------------------------------------------------------
        | Sécurité : impossible de choisir une classe sans cycle.
        |--------------------------------------------------------------------------
        */

        if (!$this->cycle_id) {
            $this->classe_id = null;

            return;
        }

        if (!$value) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification que la classe appartient bien au cycle sélectionné.
        |--------------------------------------------------------------------------
        */

        $classeValide = Classe::where('id', $value)
            ->where('cycle_id', $this->cycle_id)
            ->exists();

        if (!$classeValide) {
            $this->classe_id = null;

            return;
        }

        $this->chargerMatieres();
    }

    /*
    |--------------------------------------------------------------------------
    | CHARGER LES CLASSES
    |--------------------------------------------------------------------------
    */

    public function chargerClasses()
    {
        $this->classes = [];

        /*
        |--------------------------------------------------------------------------
        | Année obligatoire
        |--------------------------------------------------------------------------
        */

        if (!$this->annee_id) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Cycle obligatoire
        |--------------------------------------------------------------------------
        */

        if (!$this->cycle_id) {
            return;
        }

        $this->classes = Classe::query()
            ->where('cycle_id', $this->cycle_id)
            ->whereHas('annees', function ($query) {
                $query->where('annees.id', $this->annee_id);
            })
            ->orderBy('ordre')
            ->orderBy('nom')
            ->get([
                'id',
                'nom',
                'niveau',
                'cycle_id',
                'ordre',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHARGER LES MATIÈRES DE LA CLASSE
    |--------------------------------------------------------------------------
    */

    public function chargerMatieres()
    {
        $this->matieres = [];

        if (!$this->classe_id) {
            return;
        }

        $classe = Classe::find($this->classe_id);

        if (!$classe) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification cycle
        |--------------------------------------------------------------------------
        */

        if (
            !$this->cycle_id ||
            (int) $classe->cycle_id !== (int) $this->cycle_id
        ) {
            return;
        }

        $this->matieres = $classe->matieres()
            ->orderBy('nom')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | OUVRIR CRÉATION
    |--------------------------------------------------------------------------
    */

    public function nouveau()
    {
        $this->resetForm();

        $this->showModal = true;
    }

    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate();

        /*
        |--------------------------------------------------------------------------
        | Vérification supplémentaire de la classe
        |--------------------------------------------------------------------------
        */

        $classe = Classe::where('id', $this->classe_id)
            ->where('cycle_id', $this->cycle_id)
            ->whereHas('annees', function ($query) {
                $query->where('annees.id', $this->annee_id);
            })
            ->first();

        if (!$classe) {
            $this->addError(
                'classe_id',
                'La classe sélectionnée n’appartient pas au cycle et à l’année scolaire choisis.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification du doublon
        |--------------------------------------------------------------------------
        */

        $query = Enseignement::where(
            'annee_id',
            $this->annee_id
        )
            ->where(
                'classe_id',
                $this->classe_id
            )
            ->where(
                'matiere_id',
                $this->matiere_id
            );

        /*
        |--------------------------------------------------------------------------
        | Modification : exclure l'enregistrement courant
        |--------------------------------------------------------------------------
        */

        if ($this->enseignement_id) {
            $query->where(
                'id',
                '!=',
                $this->enseignement_id
            );
        }

        if ($query->exists()) {

            $this->addError(
                'matiere_id',
                'Cette matière est déjà affectée à cette classe pour cette année scolaire.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        if (!$this->enseignement_id) {

            Enseignement::create([
                'annee_id' => $this->annee_id,
                'classe_id' => $this->classe_id,
                'matiere_id' => $this->matiere_id,
                'enseignant_id' => $this->enseignant_id,
                'coefficient' => $this->coefficient,
                'volume_horaire' => $this->volume_horaire,
                'heures_par_semaine' => $this->heures_par_semaine,
                'actif' => $this->actif,
            ]);

            session()->flash(
                'success',
                'L’affectation pédagogique a été enregistrée avec succès.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MODIFICATION
        |--------------------------------------------------------------------------
        */

        else {

            $enseignement = Enseignement::findOrFail(
                $this->enseignement_id
            );

            $enseignement->update([
                'annee_id' => $this->annee_id,
                'classe_id' => $this->classe_id,
                'matiere_id' => $this->matiere_id,
                'enseignant_id' => $this->enseignant_id,
                'coefficient' => $this->coefficient,
                'volume_horaire' => $this->volume_horaire,
                'heures_par_semaine' => $this->heures_par_semaine,
                'actif' => $this->actif,
            ]);

            session()->flash(
                'success',
                'L’affectation pédagogique a été modifiée avec succès.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FERMETURE
        |--------------------------------------------------------------------------
        */

        $this->showModal = false;

        $this->resetForm();

        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | MODIFIER
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $enseignement = Enseignement::with('classe')
            ->findOrFail($id);

        $this->resetValidation();

        /*
        |--------------------------------------------------------------------------
        | ID affectation
        |--------------------------------------------------------------------------
        */

        $this->enseignement_id = $enseignement->id;

        /*
        |--------------------------------------------------------------------------
        | Année
        |--------------------------------------------------------------------------
        */

        $this->annee_id = $enseignement->annee_id;

        /*
        |--------------------------------------------------------------------------
        | Cycle récupéré depuis la classe
        |--------------------------------------------------------------------------
        */

        $this->cycle_id = optional(
            $enseignement->classe
        )->cycle_id;

        /*
        |--------------------------------------------------------------------------
        | Charger les classes uniquement après
        | avoir défini année + cycle
        |--------------------------------------------------------------------------
        */

        $this->classes = [];

        if ($this->annee_id && $this->cycle_id) {
            $this->chargerClasses();
        }

        /*
        |--------------------------------------------------------------------------
        | Classe
        |--------------------------------------------------------------------------
        */

        $this->classe_id = $enseignement->classe_id;

        /*
        |--------------------------------------------------------------------------
        | Charger les matières après la classe
        |--------------------------------------------------------------------------
        */

        $this->matieres = [];

        if ($this->classe_id) {
            $this->chargerMatieres();
        }

        $this->matiere_id = $enseignement->matiere_id;

        /*
        |--------------------------------------------------------------------------
        | Enseignant
        |--------------------------------------------------------------------------
        */

        $this->enseignant_id = $enseignement->enseignant_id;

        /*
        |--------------------------------------------------------------------------
        | Paramètres
        |--------------------------------------------------------------------------
        */

        $this->coefficient = $enseignement->coefficient;

        $this->volume_horaire =
            $enseignement->volume_horaire;

        $this->heures_par_semaine =
            $enseignement->heures_par_semaine;

        $this->actif = $enseignement->actif;

        $this->showModal = true;
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $enseignement = Enseignement::findOrFail($id);

        $enseignement->delete();

        session()->flash(
            'success',
            'L’affectation pédagogique a été supprimée avec succès.'
        );

        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVER
    |--------------------------------------------------------------------------
    */

    public function activer($id)
    {
        $enseignement = Enseignement::findOrFail($id);

        $enseignement->update([
            'actif' => true,
        ]);

        session()->flash(
            'success',
            'L’affectation pédagogique a été activée.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DÉSACTIVER
    |--------------------------------------------------------------------------
    */

    public function desactiver($id)
    {
        $enseignement = Enseignement::findOrFail($id);

        $enseignement->update([
            'actif' => false,
        ]);

        session()->flash(
            'success',
            'L’affectation pédagogique a été désactivée.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FILTRE ANNÉE
    |--------------------------------------------------------------------------
    */

    public function updatedFiltreAnneeId($value)
    {
        $this->filtre_cycle_id = null;
        $this->filtre_classe_id = null;

        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | FILTRE CYCLE
    |--------------------------------------------------------------------------
    */

    public function updatedFiltreCycleId($value)
    {
        $this->filtre_classe_id = null;

        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | FILTRE CLASSE
    |--------------------------------------------------------------------------
    */

    public function updatedFiltreClasseId($value)
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | FILTRE MATIÈRE
    |--------------------------------------------------------------------------
    */

    public function updatedFiltreMatiereId($value)
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | FILTRE ENSEIGNANT
    |--------------------------------------------------------------------------
    */

    public function updatedFiltreEnseignantId($value)
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | FILTRE ACTIF
    |--------------------------------------------------------------------------
    */

    public function updatedFiltreActif($value)
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | RECHERCHE
    |--------------------------------------------------------------------------
    */

    public function updatedSearch()
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | RESET FORMULAIRE
    |--------------------------------------------------------------------------
    */

    public function resetForm()
    {
        $this->enseignement_id = null;

        $this->annee_id = null;
        $this->cycle_id = null;
        $this->classe_id = null;
        $this->matiere_id = null;
        $this->enseignant_id = null;

        $this->coefficient = 1;
        $this->volume_horaire = null;
        $this->heures_par_semaine = null;

        $this->actif = true;

        $this->classes = [];
        $this->matieres = [];

        $this->resetValidation();
    }

    /*
    |--------------------------------------------------------------------------
    | FERMER MODAL
    |--------------------------------------------------------------------------
    */

    public function fermerModal()
    {
        $this->showModal = false;

        $this->resetForm();
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        /*
        |--------------------------------------------------------------------------
        | Années
        |--------------------------------------------------------------------------
        */

        $annees = Annee::orderByDesc('id')->get();

        /*
        |--------------------------------------------------------------------------
        | Cycles
        |--------------------------------------------------------------------------
        */

        $cycles = Cycle::orderBy('nom')->get();

        /*
        |--------------------------------------------------------------------------
        | Classes des filtres
        |--------------------------------------------------------------------------
        */

        $classesFiltre = Classe::query();

        /*
        |--------------------------------------------------------------------------
        | Filtre année
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_annee_id) {

            $classesFiltre->whereHas(
                'annees',
                function ($query) {
                    $query->where(
                        'annees.id',
                        $this->filtre_annee_id
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre cycle
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_cycle_id) {

            $classesFiltre->where(
                'cycle_id',
                $this->filtre_cycle_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classesFiltre = $classesFiltre
            ->orderBy('ordre')
            ->orderBy('nom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Matières
        |--------------------------------------------------------------------------
        */

        $matieres = Matiere::orderBy('nom')->get();

        /*
        |--------------------------------------------------------------------------
        | Enseignants
        |--------------------------------------------------------------------------
        */

        $enseignants = Enseignant::query()
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Affectations
        |--------------------------------------------------------------------------
        */

        $query = Enseignement::with([
            'annee',
            'classe',
            'matiere',
            'enseignant',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Filtre année
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_annee_id) {

            $query->where(
                'annee_id',
                $this->filtre_annee_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre cycle
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_cycle_id) {

            $query->whereHas(
                'classe',
                function ($classe) {
                    $classe->where(
                        'cycle_id',
                        $this->filtre_cycle_id
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre classe
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_classe_id) {

            $query->where(
                'classe_id',
                $this->filtre_classe_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre matière
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_matiere_id) {

            $query->where(
                'matiere_id',
                $this->filtre_matiere_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre enseignant
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_enseignant_id) {

            $query->where(
                'enseignant_id',
                $this->filtre_enseignant_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre actif
        |--------------------------------------------------------------------------
        */

        if ($this->filtre_actif !== '') {

            $query->where(
                'actif',
                $this->filtre_actif
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */

        if (trim($this->search) !== '') {

            $search = trim($this->search);

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Matière
                |--------------------------------------------------------------------------
                */

                $q->whereHas(
                    'matiere',
                    function ($matiere) use ($search) {

                        $matiere->where(
                            'nom',
                            'ILIKE',
                            "%{$search}%"
                        );
                    }
                )

                /*
                |--------------------------------------------------------------------------
                | Classe
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'classe',
                    function ($classe) use ($search) {

                        $classe->where(
                            'nom',
                            'ILIKE',
                            "%{$search}%"
                        );
                    }
                )

                /*
                |--------------------------------------------------------------------------
                | Enseignant
                |--------------------------------------------------------------------------
                */

                ->orWhereHas(
                    'enseignant',
                    function ($enseignant) use ($search) {

                        $enseignant->where(function ($e) use ($search) {

                            $e->where(
                                'nom',
                                'ILIKE',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'prenom',
                                'ILIKE',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'matricule',
                                'ILIKE',
                                "%{$search}%"
                            );
                        });
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $enseignements = $query
            ->orderByDesc('annee_id')
            ->orderBy('classe_id')
            ->orderBy('matiere_id')
            ->paginate(20);

        /*
        |--------------------------------------------------------------------------
        | Vue
        |--------------------------------------------------------------------------
        */

        return view(
            'livewire.enseignements.gestion',
            compact(
                'annees',
                'cycles',
                'classesFiltre',
                'matieres',
                'enseignants',
                'enseignements'
            )
        );
    }
}
