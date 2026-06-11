<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Annee;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Trimestre;


class TestImporter extends Component
{
    use WithFileUploads;

    // Étape d'importation
    public $step = 1;

    // Propriétés du formulaire
    public $annee_id;
    public $trimestre_id; // anciennement trimestre
    public $date;         // anciennement date_test
    public $matiere_id;
    public $type;
    public $titre;
    public $description;

    // Upload
    public $uploadedFiles = [];
    public $fileMeta = [];

    // Résultats étape 3
    public $resultMessage;

    // Chargement des combos
    public $annees;
public $matieres;
public $classes;
public $trimestres;

public function mount()
{
    $this->annees = Annee::orderBy('nom')->get();
      dd($this->annees); 
    $this->matieres = Matiere::orderBy('nom')->get();
    $this->classes = Classe::orderBy('nom')->get();
    $this->trimestres = Trimestre::all();
    $this->step = 1; // sécurité
}

    /** STEP 1 → STEP 2 : Prévisualisation */
    public function goToPreview()
    {
        $this->validate([
            'annee_id'        => 'required|numeric',
            'trimestre_id'    => 'required|numeric',
            'date'            => 'required|date',
            'matiere_id'      => 'required|numeric',
            'titre'           => 'required|string|max:255',
            'uploadedFiles.*' => 'required|file|max:15000',
        ]);

        $this->fileMeta = [];

        foreach ($this->uploadedFiles as $file) {
            $this->fileMeta[] = [
                'nom'       => $file->getClientOriginalName(),
                'size'      => $file->getSize(),
                'detected'  => $this->detectClass($file->getClientOriginalName()),
                'classe_id' => null,
            ];
        }

        $this->step = 2;
    }

    /** Exemple détection nom de fichier */
    private function detectClass($filename)
    {
        if (stripos($filename, '6eme') !== false) return '6ème';
        if (stripos($filename, '5eme') !== false) return '5ème';
        return null;
    }

    /** STEP 2 → STEP 3 : Import en base */
    public function importNow()
    {
        $this->validate([
            'fileMeta.*.classe_id' => 'required|numeric',
        ]);

        // … traitement réel d’import

        $this->resultMessage = "Importation terminée avec succès !";

        $this->step = 3;
    }

    public function render()
    {
         return view('livewire.test-importer');
    }
}
