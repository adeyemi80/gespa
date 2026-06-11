<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Support\Facades\DB;

class FraisFilter extends Component
{
    // ── Filtres en cascade ────────────────────────────────────────────────────
    public $annee_id  = '';
    public $cycle_id  = '';
    public $classe_id = '';
    public $eleve_id  = '';

    // ── Options selects ───────────────────────────────────────────────────────
    public $annees  = [];
    public $cycles  = [];
    public $classes = [];
    public $eleves  = [];

    // ── Résultats élève ───────────────────────────────────────────────────────
    public $frais_eleve  = [];
    public $totaux_eleve = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

    // ── Résultats classe ──────────────────────────────────────────────────────
    public $frais_classe  = [];
    public $totaux_classe = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

    // ── Résultats année ───────────────────────────────────────────────────────
    public $frais_annee  = [];
    public $totaux_annee = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

    // ── Onglet actif ──────────────────────────────────────────────────────────
    public $onglet = 'eleve'; // eleve | classe | annee

    public function mount()
    {
        $this->annees = Annee::orderByDesc('id')->get(['id', 'nom']);
    }

    // ── Année change ──────────────────────────────────────────────────────────
    public function updatedAnneeId($value)
    {
        $this->cycle_id  = '';
        $this->classe_id = '';
        $this->eleve_id  = '';
        $this->cycles    = [];
        $this->classes   = [];
        $this->eleves    = [];
        $this->resetResultats();

        if ($value) {
            // Cycles ayant des classes avec des frais définis pour cette année
            $cycleIds = DB::table('annee_classe_frais as acf')
                ->join('classes as c', 'c.id', '=', 'acf.classe_id')
                ->where('acf.annee_id', $value)
                ->whereNotNull('c.cycle_id')
                ->pluck('c.cycle_id')
                ->unique();

            $this->cycles = Cycle::whereIn('id', $cycleIds)
                ->orderBy('nom')
                ->get(['id', 'nom']);

            // Stats globales pour cette année
            $this->chargerStatsAnnee();
        }
    }

    // ── Cycle change ──────────────────────────────────────────────────────────
    public function updatedCycleId($value)
    {
        $this->classe_id = '';
        $this->eleve_id  = '';
        $this->classes   = [];
        $this->eleves    = [];
        $this->frais_eleve  = [];
        $this->totaux_eleve = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];
        $this->frais_classe  = [];
        $this->totaux_classe = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

        if ($value && $this->annee_id) {
            $classeIds = DB::table('annee_classe_frais')
                ->where('annee_id', $this->annee_id)
                ->pluck('classe_id')
                ->unique();

            $this->classes = Classe::whereIn('id', $classeIds)
                ->where('cycle_id', $value)
                ->orderBy('ordre')
                ->get(['id', 'nom']);
        }
    }

    // ── Classe change ─────────────────────────────────────────────────────────
    public function updatedClasseId($value)
    {
        $this->eleve_id = '';
        $this->eleves   = [];
        $this->frais_eleve  = [];
        $this->totaux_eleve = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

        if ($value && $this->annee_id) {
            $this->eleves = Eleve::whereHas('inscriptions', function ($q) use ($value) {
                $q->where('classe_id', $value)
                  ->where('annee_id', $this->annee_id);
            })
            ->orderBy('nom')
            ->get(['id', 'nom', 'prenom']);

            $this->chargerStatsClasse();
        }
    }

    // ── Élève change ──────────────────────────────────────────────────────────
    public function updatedEleveId($value)
    {
        $this->chargerStatsEleve();
    }

    // ── Onglet change ─────────────────────────────────────────────────────────
    public function setOnglet($onglet)
    {
        $this->onglet = $onglet;
    }

    // ── Stats ÉLÈVE ───────────────────────────────────────────────────────────
    public function chargerStatsEleve()
    {
        $this->frais_eleve  = [];
        $this->totaux_eleve = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

        if (!$this->annee_id || !$this->classe_id || !$this->eleve_id) return;

        $inscription = DB::table('inscriptions')
            ->where('eleve_id', $this->eleve_id)
            ->where('classe_id', $this->classe_id)
            ->where('annee_id', $this->annee_id)
            ->first();

        if (!$inscription) return;

        $rows = DB::table('inscription_frais as inf')
            ->join('frais as f', 'f.id', '=', 'inf.frais_id')
            ->where('inf.inscription_id', $inscription->id)
            ->where('inf.annee_id', $this->annee_id)
            ->select('inf.id', 'f.nom as nom_frais', 'inf.montant_frais',
                     'inf.montant_paye', 'inf.reste', 'inf.statut', 'inf.est_arriere')
            ->get();

        $this->frais_eleve = $rows->toArray();
        $this->totaux_eleve = [
            'montant_total' => $rows->sum('montant_frais'),
            'montant_paye'  => $rows->sum('montant_paye'),
            'reste'         => $rows->sum('reste'),
        ];
    }

    // ── Stats CLASSE ──────────────────────────────────────────────────────────
    public function chargerStatsClasse()
    {
        $this->frais_classe  = [];
        $this->totaux_classe = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

        if (!$this->annee_id || !$this->classe_id) return;

        // Agréger inscription_frais pour tous les élèves de cette classe/année
        $rows = DB::table('inscription_frais as inf')
            ->join('inscriptions as i', 'i.id', '=', 'inf.inscription_id')
            ->join('frais as f', 'f.id', '=', 'inf.frais_id')
            ->where('i.classe_id', $this->classe_id)
            ->where('i.annee_id', $this->annee_id)
            ->where('inf.annee_id', $this->annee_id)
            ->groupBy('f.id', 'f.nom')
            ->select(
                'f.id as frais_id',
                'f.nom as nom_frais',
                DB::raw('SUM(inf.montant_frais) as montant_frais'),
                DB::raw('SUM(inf.montant_paye)  as montant_paye'),
                DB::raw('SUM(inf.reste)          as reste'),
                DB::raw('COUNT(DISTINCT i.id)    as nb_eleves')
            )
            ->get();

        $this->frais_classe = $rows->map(function ($r) {
            $r->statut = match(true) {
                (float)$r->reste === 0.0 && (float)$r->montant_frais > 0 => 'soldé',
                (float)$r->montant_paye > 0                              => 'partiellement_payé',
                default                                                   => 'non_payé',
            };
            $r->est_arriere = false;
            return $r;
        })->toArray();

        $this->totaux_classe = [
            'montant_total' => $rows->sum('montant_frais'),
            'montant_paye'  => $rows->sum('montant_paye'),
            'reste'         => $rows->sum('reste'),
        ];
    }

    // ── Stats ANNÉE ───────────────────────────────────────────────────────────
    public function chargerStatsAnnee()
    {
        $this->frais_annee  = [];
        $this->totaux_annee = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

        if (!$this->annee_id) return;

        $rows = DB::table('inscription_frais as inf')
            ->join('inscriptions as i', 'i.id', '=', 'inf.inscription_id')
            ->join('frais as f', 'f.id', '=', 'inf.frais_id')
            ->where('i.annee_id', $this->annee_id)
            ->where('inf.annee_id', $this->annee_id)
            ->groupBy('f.id', 'f.nom')
            ->select(
                'f.id as frais_id',
                'f.nom as nom_frais',
                DB::raw('SUM(inf.montant_frais) as montant_frais'),
                DB::raw('SUM(inf.montant_paye)  as montant_paye'),
                DB::raw('SUM(inf.reste)          as reste'),
                DB::raw('COUNT(DISTINCT i.id)    as nb_eleves')
            )
            ->get();

        $this->frais_annee = $rows->map(function ($r) {
            $r->statut = match(true) {
                (float)$r->reste === 0.0 && (float)$r->montant_frais > 0 => 'soldé',
                (float)$r->montant_paye > 0                              => 'partiellement_payé',
                default                                                   => 'non_payé',
            };
            $r->est_arriere = false;
            return $r;
        })->toArray();

        $this->totaux_annee = [
            'montant_total' => $rows->sum('montant_frais'),
            'montant_paye'  => $rows->sum('montant_paye'),
            'reste'         => $rows->sum('reste'),
        ];
    }

    // ── Reset ─────────────────────────────────────────────────────────────────
    private function resetResultats()
    {
        $vide = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];
        $this->frais_eleve   = []; $this->totaux_eleve  = $vide;
        $this->frais_classe  = []; $this->totaux_classe = $vide;
        $this->frais_annee   = []; $this->totaux_annee  = $vide;
    }

    public function render()
    {
        return view('livewire.frais-filter');
    }
}