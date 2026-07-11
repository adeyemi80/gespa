<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;

class PassageWizard extends Component
{
    /*
    |--------------------------------------------------------------------------
    | STEP WIZARD
    |--------------------------------------------------------------------------
    */
    public $step = 1;

    /*
    |--------------------------------------------------------------------------
    | FILTRES
    |--------------------------------------------------------------------------
    */
    public $cycle_id        = null;
    public $annee_source_id  = null;
    public $annee_accueil_id = null;
    public $classe_id        = null;
    public $classe_accueil_id = null;

    /*
    |--------------------------------------------------------------------------
    | COLLECTIONS
    |--------------------------------------------------------------------------
    */
    public $classes         = [];
    public $classes_accueil = [];
    public $eleves          = [];

    /*
    |--------------------------------------------------------------------------
    | SELECTIONS
    |--------------------------------------------------------------------------
    */
    public $selectAll       = false;
    public $selected_eleves = [];

    /*
    |--------------------------------------------------------------------------
    | HISTORIQUE DES PASSAGES (annulation au choix)
    |--------------------------------------------------------------------------
    */
    public $historique_passages = [];
     
    public $successMessage = null;
    public $errorMessage   = null;
    public $warningMessage = null;
    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

     /*
    |--------------------------------------------------------------------------
    | RESET DES MESSAGES (à appeler au début de chaque action)
    |--------------------------------------------------------------------------
    */
    protected function resetMessages()
    {
        $this->successMessage = null;
        $this->errorMessage   = null;
        $this->warningMessage = null;
    }

    public function mount()
    {
        $this->classes         = collect();
        $this->classes_accueil = collect();
        $this->eleves          = collect();

        $anneeEnCours = Annee::where('en_cours', true)->first();

        if ($anneeEnCours) {

            $this->annee_source_id = $anneeEnCours->id;

            $anneeSuivante = Annee::where('id', '>', $anneeEnCours->id)
                ->orderBy('id')
                ->first();

            if ($anneeSuivante) {
                $this->annee_accueil_id = $anneeSuivante->id;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHARGER CLASSES PAR CYCLE
    |--------------------------------------------------------------------------
    */
    public function updatedCycleId($value)
    {
        if (!$value) {
            $this->classes  = collect();
            $this->classe_id = null;
            return;
        }

        $this->classes  = Classe::where('cycle_id', $value)
            ->orderBy('ordre')
            ->get();

        $this->classe_id = null;
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2 : CHARGER ELEVES ELIGIBLES
    |--------------------------------------------------------------------------
    */
    public function loadEleves()
    {
        $this->validate([
            'cycle_id'         => 'required',
            'classe_id'        => 'required',
            'annee_source_id'  => 'required',
            'annee_accueil_id' => 'required',
        ]);

        if ($this->annee_source_id == $this->annee_accueil_id) {
            session()->flash('error', 'L\'année d\'accueil doit être différente de l\'année source.');
            return;
        }

        $this->eleves = Inscription::with('eleve')
            ->where('classe_id', $this->classe_id)
            ->where('annee_id', $this->annee_source_id)
            ->whereHas('moyennes', function ($q) {
                $q->where('moyenne_annuelle', '>=', 10);
            })
            ->get();

        $this->selected_eleves = [];
        $this->step = 2;
    }

    /*
    |--------------------------------------------------------------------------
    | SELECT ALL
    |--------------------------------------------------------------------------
    */
    public function updatedSelectAll($value)
    {
        $this->selected_eleves = $value
            ? $this->eleves->pluck('id')->toArray()
            : [];
    }

    public function updatedSelectedEleves()
    {
        $this->selectAll =
            count($this->selected_eleves) === $this->eleves->count();
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3 : CLASSES D'ACCUEIL
    |--------------------------------------------------------------------------
    */
    public function loadClassesAccueil()
    {
        if (empty($this->selected_eleves)) {
            session()->flash('error', 'Veuillez sélectionner au moins un élève.');
            return;
        }

        $classe = Classe::find($this->classe_id);

        if (!$classe) {
            session()->flash('error', 'Classe introuvable.');
            return;
        }

        $this->classes_accueil = Classe::where('cycle_id', $classe->cycle_id)
            ->where('rang', $classe->rang + 1)
            ->orderBy('nom')
            ->get();

        $this->step = 3;
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4 : CONFIRMATION
    |--------------------------------------------------------------------------
    */
    public function confirm()
    {
        if (!$this->classe_accueil_id) {
            session()->flash('error', 'Veuillez sélectionner une classe d\'accueil.');
            return;
        }

        $this->step = 4;
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTION PASSAGE
    |--------------------------------------------------------------------------
    */
    public function executePassage()
    {
        $this->resetMessages();

        DB::beginTransaction();

        try {

            $idsPassage       = [];
            $elevesDejaPasses = [];
            $classeSource     = Classe::find($this->classe_id);
            $classeAccueil    = Classe::with('frais')->findOrFail($this->classe_accueil_id);

            foreach ($this->selected_eleves as $inscriptionId) {

                $ancienne = Inscription::with('eleve')->find($inscriptionId);
                if (!$ancienne) continue;

                $inscriptionExistante = Inscription::with('classe')
                    ->where('eleve_id', $ancienne->eleve_id)
                    ->where('annee_id', $this->annee_accueil_id)
                    ->first();

                if ($inscriptionExistante) {
                    $nomEleve = trim(($ancienne->eleve->nom ?? '') . ' ' . ($ancienne->eleve->prenom ?? ''))
                        ?: ('#' . $ancienne->eleve_id);

                    $elevesDejaPasses[] = $nomEleve
                        . ' (déjà inscrit en ' . ($inscriptionExistante->classe->nom ?? '?') . ')';

                    continue;
                }

                $nouvelleInscription = Inscription::updateOrCreate(
                    [
                        'eleve_id' => $ancienne->eleve_id,
                        'annee_id' => $this->annee_accueil_id,
                    ],
                    [
                        'classe_id'          => $this->classe_accueil_id,
                        'ancienne_classe_id' => $ancienne->classe_id,
                        'date_inscription'   => now(),
                        'passage_auto'       => true,
                    ]
                );

                $idsPassage[] = $nouvelleInscription->id;

                $fraisExistants = DB::table('inscription_frais')
                    ->where('inscription_id', $nouvelleInscription->id)
                    ->pluck('frais_id')
                    ->toArray();

                foreach ($classeAccueil->frais as $frais) {
                    if (in_array($frais->id, $fraisExistants)) continue;

                    DB::table('inscription_frais')->insert([
                        'inscription_id' => $nouvelleInscription->id,
                        'frais_id'       => $frais->id,
                        'annee_id'       => $this->annee_accueil_id,
                        'montant_frais'  => $frais->montant,
                        'montant_paye'   => 0,
                        'reste'          => $frais->montant,
                        'statut'         => 'non_payé',
                        'est_arriere'    => false,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }

            DB::commit();

            if (count($idsPassage) === 0 && !empty($elevesDejaPasses)) {
                // AUCUN passage réel : on reste à l'étape actuelle
                $this->errorMessage = 'Aucun passage effectué : tous les élèves sélectionnés sont déjà inscrits dans l\'année d\'accueil (' . implode(', ', $elevesDejaPasses) . ').';
                return; // <-- on sort ici, pas de step = 5, pas de reset des sélections
            }

            $this->historique_passages[] = [
                'label' => ($classeSource->nom ?? '?')
                    . ' → ' . $classeAccueil->nom
                    . ' · ' . count($idsPassage) . ' élève(s)'
                    . ' · ' . now()->format('d/m/Y H:i'),
                'ids' => $idsPassage,
            ];

            $this->reset([
                'selected_eleves',
                'eleves',
                'selectAll',
                'classe_accueil_id',
                'classes_accueil',
            ]);

            if (!empty($elevesDejaPasses)) {
                $this->warningMessage = count($elevesDejaPasses) . ' élève(s) déjà inscrit(s) n\'ont pas été traités : ' . implode(', ', $elevesDejaPasses);
            }

            $this->successMessage = 'Passage effectué avec succès pour ' . count($idsPassage) . ' élève(s).';
            $this->step = 5;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = $e->getMessage();
        }
    }
    /*
    |--------------------------------------------------------------------------
    | ANNULER UN PASSAGE AU CHOIX
    |--------------------------------------------------------------------------
    */
    public function annulerPassage(int $index)
    {
        if (!isset($this->historique_passages[$index])) {
            session()->flash('error', 'Passage introuvable.');
            return;
        }

        $ids   = $this->historique_passages[$index]['ids'];
        $label = $this->historique_passages[$index]['label'];

        DB::beginTransaction();

        try {

            DB::table('inscription_frais')
                ->whereIn('inscription_id', $ids)
                ->delete();

            Inscription::whereIn('id', $ids)->delete();

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | RETIRER CE PASSAGE DE L'HISTORIQUE
            |--------------------------------------------------------------------------
            */
            array_splice($this->historique_passages, $index, 1);

            session()->flash('success', 'Passage « ' . $label . ' » annulé avec succès.');

        } catch (\Exception $e) {

            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BOUTON SUIVANT
    |--------------------------------------------------------------------------
    */
    public function getCanProceedProperty()
    {
        return count($this->selected_eleves) > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */
    public function render()
    {
        return view('livewire.passage-wizard', [
            'cycles' => Cycle::all(),
            'annees' => Annee::orderBy('id')->get(),
        ]);
    }
}