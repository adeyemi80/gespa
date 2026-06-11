<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Support\Facades\DB;

class FraisFilter extends Component
{
    public $annee_id  = '';
    public $classe_id = '';
    public $eleve_id  = '';

    public $annees  = [];
    public $classes = [];
    public $eleves  = [];

    public $frais  = [];
    public $totaux = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

    public function mount()
    {
        $this->annees = Annee::orderByDesc('id')->get(['id', 'nom']);
    }

    // ── Année change ────────────────────────────────────────────────────────
    public function updatedAnneeId($value)
    {
        $this->classe_id = '';
        $this->eleve_id  = '';
        $this->classes   = [];
        $this->eleves    = [];
        $this->frais     = [];
        $this->totaux    = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

        if ($value) {
            // Les classes qui ont au moins un frais défini pour cette année
            $classeIds = DB::table('annee_classe_frais')
                ->where('annee_id', $value)
                ->pluck('classe_id')
                ->unique();

            $this->classes = Classe::whereIn('id', $classeIds)
                ->orderBy('nom')
                ->get(['id', 'nom']);
        }
    }

    // ── Classe change ───────────────────────────────────────────────────────
    public function updatedClasseId($value)
    {
        $this->eleve_id = '';
        $this->eleves   = [];
        $this->frais    = [];
        $this->totaux   = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];

        if ($value && $this->annee_id) {
            $this->eleves = Eleve::whereHas('inscriptions', function ($q) use ($value) {
                $q->where('classe_id', $value)
                  ->where('annee_id', $this->annee_id);
            })
            ->orderBy('nom')
            ->get(['id', 'nom', 'prenom']);
        }
    }

    // ── Élève change ────────────────────────────────────────────────────────
    public function updatedEleveId($value)
    {
        $this->chargerFrais();
    }

    // ── Charger les frais ───────────────────────────────────────────────────
    public function chargerFrais()
    {
        if (!$this->annee_id || !$this->classe_id || !$this->eleve_id) {
            $this->frais  = [];
            $this->totaux = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];
            return;
        }

        $inscription = DB::table('inscriptions')
            ->where('eleve_id', $this->eleve_id)
            ->where('classe_id', $this->classe_id)
            ->where('annee_id', $this->annee_id)
            ->first();

        if (!$inscription) {
            $this->frais  = [];
            $this->totaux = ['montant_total' => 0, 'montant_paye' => 0, 'reste' => 0];
            return;
        }

        // Récupère tous les frais définis pour cette classe/année
        // et join avec les paiements de l'élève (LEFT JOIN pour voir même les non payés)
        $rows = DB::table('annee_classe_frais as acf')
            ->join('frais as f', 'f.id', '=', 'acf.frais_id')
            ->leftJoin('paiements as pf', function ($join) use ($inscription) {
                $join->on('pf.frais_id', '=', 'acf.frais_id')
                     ->where('pf.inscription_id', '=', $inscription->id)
                     ->where('pf.annee_id', '=', $this->annee_id);
            })
            ->where('acf.annee_id', $this->annee_id)
            ->where('acf.classe_id', $this->classe_id)
            ->select(
                'f.id as frais_id',
                'f.nom as nom_frais',
                'acf.montant as montant_frais',
                DB::raw('COALESCE(pf.montant_paye, 0) as montant_paye'),
                DB::raw('COALESCE(pf.reste, acf.montant) as reste'),
                DB::raw("COALESCE(pf.statut, 'non_payé') as statut"),
                DB::raw('COALESCE(pf.est_arriere, false) as est_arriere'),
                'pf.id as paiement_id'
            )
            ->get();

        $this->frais = $rows->toArray();

        $this->totaux = [
            'montant_total' => $rows->sum('montant_frais'),
            'montant_paye'  => $rows->sum('montant_paye'),
            'reste'         => $rows->sum('reste'),
        ];
    }

    public function render()
    {
        return view('livewire.frais-filter');
    }
}