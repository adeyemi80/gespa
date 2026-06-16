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

    public $resultat           = [];   // recap élève unique
    public $recapClasse        = [];   // recap tous élèves d'UNE classe
    public $recapToutesClasses = [];   // [ ['classe'=>..., 'lignes'=>..., 'totaux'=>...], ... ]
    public $totaux             = [];   // totaux d'une classe

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
                      'resultat', 'recapClasse', 'totaux', 'recapToutesClasses']);
    }

    public function updatedCycleId()
    {
        $this->reset(['classe_id', 'eleve_id', 'eleves',
                      'resultat', 'recapClasse', 'totaux', 'recapToutesClasses']);
        $this->chargerClasses();
    }

    public function updatedClasseId()
    {
        $this->reset(['eleve_id', 'resultat', 'recapClasse', 'totaux', 'recapToutesClasses']);
        $this->chargerEleves();
    }

    public function updatedEleveId() { $this->resultat = []; }
    public function updatedMois()    { $this->reset(['resultat', 'recapClasse', 'totaux', 'recapToutesClasses']); }
    public function updatedMode()    { $this->reset(['resultat', 'recapClasse', 'totaux', 'recapToutesClasses']); }

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
     * Méthode commune : calcul pour UNE classe donnée
     * -------------------------------------------------------------- */
    private function calculerPourClasse(
        TdRecapService $service,
        Classe $classe,
        int $anneeId,
        int $anneeDebut
    ): array {

        $inscriptions = Inscription::with('eleve')
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->where('inscriptions.annee_id', $anneeId)
            ->where('inscriptions.classe_id', $classe->id)
            ->orderBy('eleves.nom')->orderBy('eleves.prenom')
            ->select('inscriptions.*', 'eleves.nom', 'eleves.prenom')
            ->get();

        $lignes  = [];
        $totNbTd = $totDu = $totPaye = $totReste = 0;

        foreach ($inscriptions as $insc) {
            $eleve = Eleve::findOrFail($insc->eleve_id);

            if ($this->mode === 'mois') {
                $recap = $service->recapMensuel(
                    $eleve, $classe, $anneeId, (int) $this->mois, $anneeDebut
                );
                $nbTd  = (int)   ($recap['nb_td']               ?? 0);
                $du    = (float) ($recap['montant_du_cumule']    ?? 0);
                $paye  = (float) ($recap['montant_paye_cumule']  ?? 0);
                $reste = (float) ($recap['reste_a_payer_cumule'] ?? 0);
            } else {
                $recap = $service->recapAnnuel(
                    $eleve, $classe, $anneeId, $anneeDebut
                );
                $nbTd  = (int)   ($recap['nb_td']        ?? 0);
                $du    = (float) ($recap['montant_du']    ?? 0);
                $paye  = (float) ($recap['montant_paye']  ?? 0);
                $reste = (float) ($recap['reste_a_payer'] ?? 0);
            }

            $lignes[] = [
                'nom'    => $insc->nom,
                'prenom' => $insc->prenom,
                'nb_td'  => $nbTd,
                'du'     => $du,
                'paye'   => $paye,
                'reste'  => $reste,
            ];

            $totNbTd  += $nbTd;
            $totDu    += $du;
            $totPaye  += $paye;
            $totReste += $reste;
        }

        return [
            'classe' => $classe,
            'lignes' => $lignes,
            'totaux' => [
                'nb_td' => $totNbTd,
                'du'    => $totDu,
                'paye'  => $totPaye,
                'reste' => $totReste,
            ],
        ];
    }

    /* ---------------------------------------------------------------
     * Récap d'UNE classe sélectionnée
     * -------------------------------------------------------------- */
    public function calculerClasse(TdRecapService $service)
    {
        $this->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'mois'      => $this->mode === 'mois'
                            ? 'required|integer|min:1|max:12'
                            : 'nullable',
        ]);

        $annee      = Annee::findOrFail($this->annee_id);
        $classe     = Classe::findOrFail($this->classe_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;

        $result = $this->calculerPourClasse($service, $classe, $this->annee_id, $anneeDebut);

        $this->recapClasse        = $result['lignes'];
        $this->totaux             = $result['totaux'];
        $this->recapToutesClasses = [];
    }

    /* ---------------------------------------------------------------
     * Récap de TOUTES les classes (filtrées par cycle si sélectionné)
     * -------------------------------------------------------------- */
    public function calculerToutesClasses(TdRecapService $service)
    {
        $this->validate([
            'annee_id' => 'required|exists:annees,id',
            'mois'     => $this->mode === 'mois'
                           ? 'required|integer|min:1|max:12'
                           : 'nullable',
        ]);

        $annee      = Annee::findOrFail($this->annee_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;

        $query = Classe::orderByNiveau();
        if ($this->cycle_id) {
            $query->where('cycle_id', $this->cycle_id);
        }
        $toutesClasses = $query->get();

        $this->recapToutesClasses = [];
        $this->recapClasse        = [];
        $this->totaux             = [];

        foreach ($toutesClasses as $classe) {
            $result = $this->calculerPourClasse($service, $classe, $this->annee_id, $anneeDebut);
            // N'inclure que les classes ayant au moins un élève inscrit
            if (!empty($result['lignes'])) {
                $this->recapToutesClasses[] = $result;
            }
        }
    }

    /* ---------------------------------------------------------------
     * Calcul élève unique
     * -------------------------------------------------------------- */
    public function calculer(TdRecapService $service)
    {
        $this->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'eleve_id'  => 'required|exists:eleves,id',
            'mois'      => 'required|integer|min:1|max:12',
        ]);

        $eleve      = Eleve::findOrFail($this->eleve_id);
        $classe     = Classe::findOrFail($this->classe_id);
        $anneeDebut = (int) Carbon::parse(Annee::find($this->annee_id)->debut)->year;

        $this->resultat = $service->recapMensuel(
            $eleve, $classe,
            $this->annee_id,
            (int) $this->mois,
            $anneeDebut
        );
    }

    public function render()
    {
        return view('livewire.td-recap-manager');
    }
}