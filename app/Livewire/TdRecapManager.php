<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Services\TdRecapService;
use Carbon\Carbon;

class TdRecapManager extends Component
{
    public $annee_id;
    public $cycle_id;
    public $classe_id;
    public $eleve_id;
    public $mois;
    public string $mode = 'mois'; // 'mois' | 'annee'

    public $annees  = [];
    public $cycles  = [];
    public $classes = [];
    public $eleves  = [];

    public $resultat      = [];   // recap élève unique (ancien mode)
    public $recapClasse   = [];   // recap tous élèves d'une classe
    public $totaux        = [];   // ligne totaux du tableau

    public function mount()
    {
        $this->annees   = Annee::orderByDesc('id')->get();
        $this->annee_id = Annee::where('en_cours', true)->first()?->id
                          ?? $this->annees->first()?->id;

        $this->cycles = Cycle::orderBy('id')->get();
        $this->mois   = now()->month;
    }

    public function updatedAnneeId()
    {
        $this->reset(['cycle_id', 'classe_id', 'eleve_id', 'classes', 'eleves',
                      'resultat', 'recapClasse', 'totaux']);
    }

    public function updatedCycleId()
    {
        $this->reset(['classe_id', 'eleve_id', 'eleves', 'resultat', 'recapClasse', 'totaux']);
        $this->chargerClasses();
    }

    public function updatedClasseId()
    {
        $this->reset(['eleve_id', 'resultat', 'recapClasse', 'totaux']);
        $this->chargerEleves();
    }

    public function updatedEleveId()  { $this->resultat = []; }
    public function updatedMois()     { $this->reset(['resultat', 'recapClasse', 'totaux']); }
    public function updatedMode()     { $this->reset(['resultat', 'recapClasse', 'totaux']); }

    private function chargerClasses()
    {
        $this->classes = $this->cycle_id
            ? Classe::where('cycle_id', $this->cycle_id)->orderByNiveau()->get()
            : [];
    }

    private function chargerEleves()
    {
        if (!$this->classe_id || !$this->annee_id) { $this->eleves = []; return; }

        $this->eleves = Inscription::with('eleve')
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->where('inscriptions.annee_id', $this->annee_id)
            ->where('inscriptions.classe_id', $this->classe_id)
            ->orderBy('eleves.nom')->orderBy('eleves.prenom')
            ->select('inscriptions.*', 'eleves.nom', 'eleves.prenom')
            ->get();
    }

    /* ---------------------------------------------------------------
     * Calcul récapitulatif pour TOUTE une classe
     * -------------------------------------------------------------- */
    public function calculerClasse(TdRecapService $service)
    {
        $this->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'mois'      => $this->mode === 'mois' ? 'required|integer|min:1|max:12' : 'nullable',
        ]);

        $classe = Classe::findOrFail($this->classe_id);
        $annee  = Annee::findOrFail($this->annee_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;

        // Tous les élèves inscrits dans cette classe cette année
        $inscriptions = Inscription::with('eleve')
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->where('inscriptions.annee_id', $this->annee_id)
            ->where('inscriptions.classe_id', $this->classe_id)
            ->orderBy('eleves.nom')->orderBy('eleves.prenom')
            ->select('inscriptions.*', 'eleves.nom', 'eleves.prenom', 'eleves.id as eleve_id_col')
            ->get();

        $lignes = [];
        $totNbTd = $totDu = $totPaye = $totReste = 0;

        foreach ($inscriptions as $insc) {
            $eleve = Eleve::findOrFail($insc->eleve_id);

            if ($this->mode === 'mois') {
                $recap = $service->recapMensuel($eleve, $classe, $this->annee_id, (int)$this->mois, $anneeDebut);
                $nbTd  = $recap['nb_td_mois']        ?? 0;
                $du    = $recap['montant_du_cumule']  ?? 0;
                $paye  = $recap['montant_paye_cumule']?? 0;
                $reste = $recap['reste_a_payer_cumule']?? 0;
            } else {
                // mode annee : on appelle recapAnnuel si disponible, sinon on agrège les mois
                $recap = $service->recapAnnuel($eleve, $classe, $this->annee_id, $anneeDebut);
                $nbTd  = $recap['nb_td_annee']        ?? 0;
                $du    = $recap['montant_du_annee']    ?? 0;
                $paye  = $recap['montant_paye_annee']  ?? 0;
                $reste = $recap['reste_a_payer_annee'] ?? 0;
            }

            $lignes[] = [
                'nom'    => $insc->nom,
                'prenom' => $insc->prenom,
                'nb_td'  => $nbTd,
                'du'     => $du,
                'paye'   => $paye,
                'reste'  => $reste,
            ];

            $totNbTd += $nbTd;
            $totDu   += $du;
            $totPaye += $paye;
            $totReste+= $reste;
        }

        $this->recapClasse = $lignes;
        $this->totaux = [
            'nb_td' => $totNbTd,
            'du'    => $totDu,
            'paye'  => $totPaye,
            'reste' => $totReste,
        ];
    }

    /* Calcul élève unique (inchangé) */
    public function calculer(TdRecapService $service)
    {
        $this->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'eleve_id'  => 'required|exists:eleves,id',
            'mois'      => 'required|integer|min:1|max:12',
        ]);

        $eleve  = Eleve::findOrFail($this->eleve_id);
        $classe = Classe::findOrFail($this->classe_id);
        $anneeDebut = (int) Carbon::parse(Annee::find($this->annee_id)->debut)->year;

        $this->resultat = $service->recapMensuel($eleve, $classe, $this->annee_id, (int)$this->mois, $anneeDebut);
    }

    public function render()
    {
        return view('livewire.td-recap-manager');
    }
}