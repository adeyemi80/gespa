<?php

namespace App\Livewire\Eleves;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Eleve;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ModifierEleve extends Component
{
    use WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | Sélections
    |--------------------------------------------------------------------------
    */

    public $annee_id = '';
    public $cycle_id = '';
    public $classe_id = '';
    public $inscription_id = '';

    /*
    |--------------------------------------------------------------------------
    | Listes
    |--------------------------------------------------------------------------
    */

    public $annees = [];
    public $cycles = [];
    public $classes = [];
    public $inscriptions = [];

    /*
    |--------------------------------------------------------------------------
    | Élève sélectionné
    |--------------------------------------------------------------------------
    */

    public $eleve_id;

    /*
    |--------------------------------------------------------------------------
    | Informations communes élève / inscription
    |--------------------------------------------------------------------------
    */

    public $nom;
    public $prenom;
    public $date_naissance;
    public $sexe;
    public $nationalite;
    public $lieu_naissance;
    public $matricule;
    public $statut;
    public $paren_id;
    public $numero_ordre;
    public $numeducmaster;

    /*
    |--------------------------------------------------------------------------
    | Photo
    |--------------------------------------------------------------------------
    */

    public $photo;
    public $ancienne_photo;

    /*
    |--------------------------------------------------------------------------
    | État
    |--------------------------------------------------------------------------
    */

    public $eleveSelectionne = false;

    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->annees = Annee::orderByDesc('id')->get();

        $this->cycles = Cycle::orderBy('nom')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Année sélectionnée
    |--------------------------------------------------------------------------
    */

    public function updatedAnneeId()
    {
        $this->cycle_id = '';
        $this->classe_id = '';
        $this->inscription_id = '';

        $this->classes = [];
        $this->inscriptions = [];

        $this->resetFormEleve();
    }

    /*
    |--------------------------------------------------------------------------
    | Cycle sélectionné
    |--------------------------------------------------------------------------
    */

    public function updatedCycleId()
    {
        $this->classe_id = '';
        $this->inscription_id = '';

        $this->inscriptions = [];

        $this->resetFormEleve();

        if (!$this->annee_id || !$this->cycle_id) {
            $this->classes = [];

            return;
        }

        $this->classes = Classe::query()
            ->where('cycle_id', $this->cycle_id)
            ->whereHas('annees', function ($query) {
                $query->where('annees.id', $this->annee_id);
            })
            ->orderBy('nom')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Classe sélectionnée
    |--------------------------------------------------------------------------
    */

    public function updatedClasseId()
    {
        $this->inscription_id = '';

        $this->inscriptions = [];

        $this->resetFormEleve();

        if (!$this->annee_id || !$this->classe_id) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT :
        | On recherche les élèves à partir de INSCRIPTIONS.
        |--------------------------------------------------------------------------
        */

        $this->inscriptions = Inscription::query()
            ->with('eleve')
            ->where('annee_id', $this->annee_id)
            ->where('classe_id', $this->classe_id)
            ->whereHas('eleve')
            ->get()
            ->sortBy(function ($inscription) {
                return strtoupper(
                    ($inscription->eleve->nom ?? '') . ' ' .
                    ($inscription->eleve->prenom ?? '')
                );
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Afficher l'élève
    |--------------------------------------------------------------------------
    */

    public function afficherEleve()
    {
        $this->validate([
            'annee_id' => [
                'required',
                'exists:annees,id'
            ],

            'cycle_id' => [
                'required',
                'exists:cycles,id'
            ],

            'classe_id' => [
                'required',
                'exists:classes,id'
            ],

            'inscription_id' => [
                'required',

                Rule::exists('inscriptions', 'id')
                    ->where('annee_id', $this->annee_id)
                    ->where('classe_id', $this->classe_id),
            ],
        ], [
            'annee_id.required' =>
                'Veuillez sélectionner une année.',

            'cycle_id.required' =>
                'Veuillez sélectionner un cycle.',

            'classe_id.required' =>
                'Veuillez sélectionner une classe.',

            'inscription_id.required' =>
                'Veuillez sélectionner un élève.',

            'inscription_id.exists' =>
                'L’inscription sélectionnée ne correspond pas à cette année et cette classe.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Récupérer l'inscription
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::with('eleve')
            ->where('id', $this->inscription_id)
            ->where('annee_id', $this->annee_id)
            ->where('classe_id', $this->classe_id)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Récupérer l'élève à partir de l'inscription
        |--------------------------------------------------------------------------
        */

        $eleve = $inscription->eleve;

        if (!$eleve) {
            $this->addError(
                'inscription_id',
                'Aucun élève n’est associé à cette inscription.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Charger les informations
        |--------------------------------------------------------------------------
        */

        $this->eleve_id = $eleve->id;

        $this->nom = $eleve->nom;
        $this->prenom = $eleve->prenom;
        $this->date_naissance = $eleve->date_naissance;
        $this->sexe = $eleve->sexe;
        $this->nationalite = $eleve->nationalite;
        $this->lieu_naissance = $eleve->lieu_naissance;
        $this->matricule = $eleve->matricule;
        $this->statut = $eleve->statut;
        $this->paren_id = $eleve->paren_id;
        $this->numero_ordre = $eleve->numero_ordre;
        $this->numeducmaster = $eleve->numeducmaster;

        $this->ancienne_photo = $eleve->photo;

        $this->photo = null;

        $this->eleveSelectionne = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Enregistrer les modifications
    |--------------------------------------------------------------------------
    */

    public function enregistrer()
    {
        if (!$this->inscription_id) {
            $this->addError(
                'inscription_id',
                'Aucune inscription sélectionnée.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Récupérer l'inscription et l'élève
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::with('eleve')
            ->where('id', $this->inscription_id)
            ->where('annee_id', $this->annee_id)
            ->where('classe_id', $this->classe_id)
            ->first();

        if (!$inscription) {
            $this->addError(
                'inscription_id',
                'L\'inscription sélectionnée est introuvable.'
            );

            return;
        }

        $eleve = $inscription->eleve;

        if (!$eleve) {
            $this->addError(
                'inscription_id',
                'L\'élève lié à cette inscription est introuvable.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'nom' => [
                'required',
                'string',
                'max:255'
            ],

            'prenom' => [
                'required',
                'string',
                'max:255'
            ],

            'date_naissance' => [
                'nullable',
                'date'
            ],

            'sexe' => [
                'required',
                Rule::in(['M', 'F'])
            ],

            'nationalite' => [
                'nullable',
                'string',
                'max:100'
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:255'
            ],

            'matricule' => [
                'required',
                'string',
                'max:100',

                Rule::unique('eleves', 'matricule')
                    ->ignore($eleve->id),
            ],

            'statut' => [
                'required',
                Rule::in([
                    'passant',
                    'redoublant',
                ]),
            ],

            'paren_id' => [
                'nullable',
                'exists:parens,id'
            ],

            'numero_ordre' => [
                'nullable',
                'string',
                'max:100'
            ],

            'numeducmaster' => [
                'nullable',
                'string',
                'max:100'
            ],

            'photo' => [
                'nullable',
                'image',
                'max:5120'
            ],
        ], [
            'nom.required' =>
                'Le nom est obligatoire.',

            'prenom.required' =>
                'Le prénom est obligatoire.',

            'sexe.required' =>
                'Veuillez sélectionner le sexe.',

            'matricule.required' =>
                'Le matricule est obligatoire.',

            'matricule.unique' =>
                'Ce matricule existe déjà.',

            'photo.image' =>
                'Le fichier doit être une image.',

            'photo.max' =>
                'La photo ne doit pas dépasser 5 Mo.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        |
        | Les deux tables sont modifiées ensemble.
        |
        */

        DB::transaction(function () use ($eleve, $inscription) {

            /*
            |--------------------------------------------------------------------------
            | 1. Mise à jour de ELEVE
            |--------------------------------------------------------------------------
            */

            $eleve->nom = strtoupper(trim($this->nom));

            $eleve->prenom = trim($this->prenom);

            $eleve->date_naissance = $this->date_naissance;

            $eleve->sexe = $this->sexe;

            $eleve->nationalite = $this->nationalite;

            $eleve->lieu_naissance = $this->lieu_naissance;

            $eleve->matricule = trim($this->matricule);

            $eleve->statut = $this->statut;

            $eleve->paren_id = $this->paren_id ?: null;

            $eleve->numero_ordre = $this->numero_ordre ?: null;

            $eleve->numeducmaster = $this->numeducmaster ?: null;

            /*
            |--------------------------------------------------------------------------
            | Photo
            |--------------------------------------------------------------------------
            */

            if ($this->photo) {

                $anciennePhoto = $eleve->photo;

                $nouvellePhoto = $this->photo->store(
                    'photos_eleves',
                    'public'
                );

                $eleve->photo = $nouvellePhoto;

                /*
                | La suppression de l'ancienne photo se fait après
                | l'enregistrement de la nouvelle.
                */

                if (
                    $anciennePhoto &&
                    Storage::disk('public')->exists($anciennePhoto)
                ) {
                    Storage::disk('public')->delete($anciennePhoto);
                }
            }

            $eleve->save();


            /*
            |--------------------------------------------------------------------------
            | 2. Mise à jour de INSCRIPTION
            |--------------------------------------------------------------------------
            |
            | L'inscription conserve l'année et la classe sélectionnées.
            |
            | Si ta table inscriptions possède des champs spécifiques
            | à l'inscription, ils peuvent être ajoutés ici.
            |
            */

            $inscription->annee_id = $this->annee_id;

            $inscription->classe_id = $this->classe_id;

            /*
            |--------------------------------------------------------------------------
            | Si ton inscription possède ces colonnes, décommente :
            |--------------------------------------------------------------------------
            |
            | $inscription->statut = $this->statut;
            | $inscription->numero_ordre = $this->numero_ordre;
            |
            */

            $inscription->save();
        });

        /*
        |--------------------------------------------------------------------------
        | Actualiser l'affichage
        |--------------------------------------------------------------------------
        */

        $this->ancienne_photo = $eleve->fresh()->photo;

        $this->photo = null;

        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'success',
            'Les informations de l’élève et son inscription ont été mises à jour avec succès.'
        );

        $this->dispatch(
            'notification',
            type: 'success',
            message: 'Les modifications ont été enregistrées avec succès.'
        );

        /*
        |--------------------------------------------------------------------------
        | Recharger la liste des inscriptions
        |--------------------------------------------------------------------------
        */

        $this->chargerInscriptions();
    }

    /*
    |--------------------------------------------------------------------------
    | Recharger les inscriptions
    |--------------------------------------------------------------------------
    */

    private function chargerInscriptions()
    {
        if (!$this->annee_id || !$this->classe_id) {
            $this->inscriptions = [];

            return;
        }

        $this->inscriptions = Inscription::query()
            ->with('eleve')
            ->where('annee_id', $this->annee_id)
            ->where('classe_id', $this->classe_id)
            ->whereHas('eleve')
            ->get()
            ->sortBy(function ($inscription) {
                return strtoupper(
                    ($inscription->eleve->nom ?? '') . ' ' .
                    ($inscription->eleve->prenom ?? '')
                );
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Réinitialiser le formulaire
    |--------------------------------------------------------------------------
    */

    private function resetFormEleve()
    {
        $this->eleveSelectionne = false;

        $this->eleve_id = null;

        $this->nom = null;
        $this->prenom = null;
        $this->date_naissance = null;
        $this->sexe = null;
        $this->nationalite = null;
        $this->lieu_naissance = null;
        $this->matricule = null;
        $this->statut = null;
        $this->paren_id = null;
        $this->numero_ordre = null;
        $this->numeducmaster = null;

        $this->photo = null;
        $this->ancienne_photo = null;
    }

    /*
    |--------------------------------------------------------------------------
    | Vue
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.eleves.modifier-eleve');
    }
}
