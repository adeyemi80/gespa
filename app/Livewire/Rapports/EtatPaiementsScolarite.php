<?php

namespace App\Livewire\Rapports;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EtatPaiementsScolarite extends Component
{
    public $anneeId = null;
    public $cycleId = null;
    public $classeId = null;
    public $eleveId = null;

    /** Taux maximum de scolarité payée (en %) : null = pas de filtre. */
    public $tauxMax = null;

    /**
     * Critère de classement :
     * nom | paye_desc | paye_asc | taux_desc | taux_asc | reste_desc
     */
    public $tri = 'nom';

    /** Identifiants d'inscriptions cochées pour l'export PDF. */
    public array $selection = [];

    public function mount(): void
    {
        $this->anneeId = Annee::query()
            ->where('en_cours', true)
            ->value('id')
            ?? Annee::query()->orderByDesc('id')->value('id');
    }

    public function updatedAnneeId(): void
    {
        $this->cycleId = null;
        $this->classeId = null;
        $this->eleveId = null;
        $this->selection = [];
    }

    public function updatedCycleId(): void
    {
        $this->classeId = null;
        $this->eleveId = null;
        $this->selection = [];
    }

    public function updatedClasseId(): void
    {
        $this->eleveId = null;
        $this->selection = [];
    }

    public function updatedEleveId(): void
    {
        $this->selection = [];
    }

    public function updatedTauxMax(): void
    {
        $this->selection = [];
    }

    /**
     * Coche / décoche toutes les inscriptions actuellement affichées.
     */
    public function selectionnerTout(): void
    {
        $ids = $this->lignesAffichees($this->calculerEtat())
            ->pluck('inscription_id')
            ->map(fn ($id) => (string) $id)
            ->all();

        $dejaToutes = count($ids) > 0
            && count(array_diff($ids, array_map('strval', $this->selection))) === 0;

        $this->selection = $dejaToutes ? [] : $ids;
    }

    public function viderSelection(): void
    {
        $this->selection = [];
    }

    private function requeteInscriptions()
    {
        return DB::table('inscriptions as i')
            ->join('eleves as e', 'e.id', '=', 'i.eleve_id')
            ->join('classes as cl', 'cl.id', '=', 'i.classe_id')
            ->join('cycles as cy', 'cy.id', '=', 'cl.cycle_id')
            ->join('annees as a', 'a.id', '=', 'i.annee_id')
            ->where('i.annee_id', $this->anneeId)

            ->when($this->cycleId, function ($query) {
                $query->where('cl.cycle_id', $this->cycleId);
            })

            ->when($this->classeId, function ($query) {
                $query->where('i.classe_id', $this->classeId);
            })

            ->when($this->eleveId, function ($query) {
                $query->where('i.eleve_id', $this->eleveId);
            })

            ->select([
                'i.id as inscription_id',
                'i.eleve_id',
                'i.classe_id',
                'i.annee_id',
                'i.date_inscription',
                'e.nom as nom_eleve',
                'e.prenom as prenom_eleve',
                'e.matricule',
                'cl.nom as nom_classe',
                'cy.nom as nom_cycle',
                'a.nom as nom_annee',
            ])

            ->selectRaw("
                CASE
                    WHEN NOT EXISTS (
                        SELECT 1
                        FROM inscriptions AS precedente
                        WHERE precedente.eleve_id = i.eleve_id
                          AND (
                              precedente.date_inscription < i.date_inscription
                              OR (
                                  precedente.date_inscription = i.date_inscription
                                  AND precedente.id < i.id
                              )
                          )
                    )
                    THEN TRUE
                    ELSE FALSE
                END AS est_nouveau
            ")

            ->orderBy('e.nom')
            ->orderBy('e.prenom');
    }

    private function pct($paye, $attendu): float
    {
        return $attendu > 0 ? round(($paye / $attendu) * 100, 1) : 0.0;
    }

    /**
     * Lignes de base (filtres année / cycle / classe / élève),
     * sans filtre de taux ni tri.
     */
    private function calculerEtat(): Collection
    {
        if (!$this->anneeId) {
            return collect();
        }

        $inscriptions = $this->requeteInscriptions()->get();
        $ids = $inscriptions->pluck('inscription_id');

        if ($ids->isEmpty()) {
            return collect();
        }

        $fraisParInscription = DB::table('inscription_frais as inf')
            ->join('frais as f', 'f.id', '=', 'inf.frais_id')
            ->join('categories_recettes as cr', 'cr.id', '=', 'f.categorie_recette_id')
            ->where('inf.annee_id', $this->anneeId)
            ->whereIn('inf.inscription_id', $ids)
            ->whereIn('cr.code', ['SCO', 'INS', 'REI'])
            ->select('inf.inscription_id')
            ->selectRaw("COALESCE(SUM(CASE WHEN cr.code = 'SCO' THEN COALESCE(inf.montant_frais, 0) ELSE 0 END), 0) AS attendu_scolarite")
            ->selectRaw("COALESCE(SUM(CASE WHEN cr.code = 'SCO' THEN COALESCE(inf.montant_paye, 0) ELSE 0 END), 0) AS paye_scolarite")
            ->selectRaw("COALESCE(SUM(CASE WHEN cr.code = 'SCO' THEN COALESCE(inf.reste, 0) ELSE 0 END), 0) AS reste_scolarite")
            ->selectRaw("COALESCE(SUM(CASE WHEN cr.code IN ('INS', 'REI') THEN COALESCE(inf.montant_frais, 0) ELSE 0 END), 0) AS attendu_inscription")
            ->selectRaw("COALESCE(SUM(CASE WHEN cr.code IN ('INS', 'REI') THEN COALESCE(inf.montant_paye, 0) ELSE 0 END), 0) AS paye_inscription")
            ->selectRaw("COALESCE(SUM(CASE WHEN cr.code IN ('INS', 'REI') THEN COALESCE(inf.reste, 0) ELSE 0 END), 0) AS reste_inscription")
            ->groupBy('inf.inscription_id')
            ->get()
            ->keyBy('inscription_id');

        return $inscriptions->map(function ($inscription) use ($fraisParInscription) {
            $frais = $fraisParInscription[$inscription->inscription_id] ?? null;

            $estNouveau = (bool) $inscription->est_nouveau;

            $attenduScolarite = (float) ($frais->attendu_scolarite ?? 0);
            $payeScolarite = (float) ($frais->paye_scolarite ?? 0);
            $resteScolarite = (float) ($frais->reste_scolarite ?? 0);

            $attenduInscription = $estNouveau ? (float) ($frais->attendu_inscription ?? 0) : 0;
            $payeInscription = $estNouveau ? (float) ($frais->paye_inscription ?? 0) : 0;
            $resteInscription = $estNouveau ? (float) ($frais->reste_inscription ?? 0) : 0;

            return [
                'inscription_id' => $inscription->inscription_id,
                'eleve_id' => $inscription->eleve_id,
                'nom' => trim($inscription->nom_eleve . ' ' . $inscription->prenom_eleve),
                'matricule' => $inscription->matricule ?? '-',
                'cycle' => $inscription->nom_cycle,
                'classe' => $inscription->nom_classe,
                'annee' => $inscription->nom_annee,
                'est_nouveau' => $estNouveau,

                'attendu_scolarite' => $attenduScolarite,
                'paye_scolarite' => $payeScolarite,
                'reste_scolarite' => $resteScolarite,
                // null si aucune scolarité attendue (taux non calculable)
                'taux_scolarite' => $attenduScolarite > 0
                    ? round(($payeScolarite / $attenduScolarite) * 100, 1)
                    : null,

                'attendu_inscription' => $attenduInscription,
                'paye_inscription' => $payeInscription,
                'reste_inscription' => $resteInscription,

                'attendu_general' => $attenduScolarite + $attenduInscription,
                'paye_general' => $payeScolarite + $payeInscription,
                'reste_general' => $resteScolarite + $resteInscription,
            ];
        })->values();
    }

    /**
     * Applique le filtre « taux ≤ X % » puis le tri choisi.
     */
    private function lignesAffichees(Collection $lignes): Collection
    {
        if ($this->tauxMax !== null && $this->tauxMax !== '') {
            $max = (float) $this->tauxMax;

            $lignes = $lignes->filter(
                fn ($l) => $l['taux_scolarite'] !== null
                    && $l['taux_scolarite'] <= $max
            );
        }

        $lignes = match ($this->tri) {
            'paye_desc' => $lignes->sortByDesc('paye_scolarite'),
            'paye_asc' => $lignes->sortBy('paye_scolarite'),
            'taux_desc' => $lignes->sortByDesc(fn ($l) => $l['taux_scolarite'] ?? -1),
            'taux_asc' => $lignes->sortBy(fn ($l) => $l['taux_scolarite'] ?? -1),
            'reste_desc' => $lignes->sortByDesc('reste_scolarite'),
            default => $lignes,
        };

        return $lignes->values();
    }

    /**
     * Totaux + taux de recouvrement pour un ensemble de lignes.
     */
    private function calculerTotaux(Collection $lignes): array
    {
        $attenduScolarite = $lignes->sum('attendu_scolarite');
        $payeScolarite = $lignes->sum('paye_scolarite');
        $attenduInscription = $lignes->sum('attendu_inscription');
        $payeInscription = $lignes->sum('paye_inscription');
        $attenduGeneral = $lignes->sum('attendu_general');
        $payeGeneral = $lignes->sum('paye_general');

        return [
            'effectif' => $lignes->count(),
            'nouveaux' => $lignes->where('est_nouveau', true)->count(),

            'attenduScolarite' => $attenduScolarite,
            'payeScolarite' => $payeScolarite,
            'resteScolarite' => $lignes->sum('reste_scolarite'),
            'tauxScolarite' => $this->pct($payeScolarite, $attenduScolarite),

            'attenduInscription' => $attenduInscription,
            'payeInscription' => $payeInscription,
            'resteInscription' => $lignes->sum('reste_inscription'),
            'tauxInscription' => $this->pct($payeInscription, $attenduInscription),

            'attenduGeneral' => $attenduGeneral,
            'payeGeneral' => $payeGeneral,
            'resteGeneral' => $lignes->sum('reste_general'),
            'tauxGeneral' => $this->pct($payeGeneral, $attenduGeneral),
        ];
    }

    /**
     * Répartition des inscriptions par tranche de taux de scolarité payée.
     */
    private function calculerTranches(Collection $lignes): array
    {
        $tranches = [
            ['label' => '0 %', 'count' => 0, 'color' => 'danger'],
            ['label' => '1 – 25 %', 'count' => 0, 'color' => 'danger'],
            ['label' => '26 – 50 %', 'count' => 0, 'color' => 'warning'],
            ['label' => '51 – 75 %', 'count' => 0, 'color' => 'warning'],
            ['label' => '76 – 99 %', 'count' => 0, 'color' => 'info'],
            ['label' => '100 %', 'count' => 0, 'color' => 'success'],
        ];

        foreach ($lignes as $l) {
            $t = $l['taux_scolarite'];

            if ($t === null) {
                continue;
            }

            $i = match (true) {
                $t <= 0 => 0,
                $t <= 25 => 1,
                $t <= 50 => 2,
                $t <= 75 => 3,
                $t < 100 => 4,
                default => 5,
            };

            $tranches[$i]['count']++;
        }

        return $tranches;
    }

    private function libelleTri(): string
    {
        return match ($this->tri) {
            'paye_desc' => 'Scolarité payée (décroissant)',
            'paye_asc' => 'Scolarité payée (croissant)',
            'taux_desc' => 'Taux de paiement (décroissant)',
            'taux_asc' => 'Taux de paiement (croissant)',
            'reste_desc' => 'Reste à payer (décroissant)',
            default => 'Ordre alphabétique',
        };
    }

    /**
     * Export PDF : inscriptions cochées si une sélection existe,
     * sinon toute la liste filtrée (taux + tri appliqués).
     */
    public function exporterPdf()
    {
        $lignes = $this->lignesAffichees($this->calculerEtat());

        $exportSelection = count($this->selection) > 0;

        if ($exportSelection) {
            $selection = array_map('strval', $this->selection);

            $lignes = $lignes
                ->filter(fn ($l) => in_array((string) $l['inscription_id'], $selection, true))
                ->values();
        }

        $annee = Annee::find($this->anneeId);
        $cycle = $this->cycleId ? Cycle::find($this->cycleId) : null;
        $classe = $this->classeId ? Classe::find($this->classeId) : null;

        $eleve = $this->eleveId
            ? DB::table('eleves')->where('id', $this->eleveId)->first()
            : null;

        $pdf = Pdf::loadView(
            'livewire.rapports.pdf-etat-paiements-scolarite',
            [
                'lignes' => $lignes,
                'totaux' => $this->calculerTotaux($lignes),
                'annee' => $annee,
                'cycle' => $cycle,
                'classe' => $classe,
                'eleve' => $eleve,
                'tauxMax' => ($this->tauxMax !== null && $this->tauxMax !== '')
                    ? (float) $this->tauxMax
                    : null,
                'libelleTri' => $this->libelleTri(),
                'exportSelection' => $exportSelection,
                'dateEdition' => now()->format('d/m/Y H:i'),
            ]
        )->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'etat-paiements-scolarite-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }

    public function render()
    {
        $annees = Annee::query()->orderByDesc('id')->get();
        $cycles = Cycle::query()->orderBy('ordre')->get();

        $classesQuery = Classe::query();

        if ($this->cycleId) {
            $classesQuery->where('cycle_id', $this->cycleId);
        }

        $classes = $classesQuery->orderBy('ordre')->get();

        $eleves = collect();

        if ($this->anneeId) {
            $eleves = DB::table('inscriptions as i')
                ->join('eleves as e', 'e.id', '=', 'i.eleve_id')
                ->join('classes as cl', 'cl.id', '=', 'i.classe_id')
                ->where('i.annee_id', $this->anneeId)
                ->when($this->cycleId, fn ($q) => $q->where('cl.cycle_id', $this->cycleId))
                ->when($this->classeId, fn ($q) => $q->where('i.classe_id', $this->classeId))
                ->select('e.id', 'e.nom', 'e.prenom', 'e.matricule')
                ->distinct()
                ->orderBy('e.nom')
                ->orderBy('e.prenom')
                ->get();
        }

        $base = $this->calculerEtat();
        $affichees = $this->lignesAffichees($base);

        return view('livewire.rapports.etat-paiements-scolarite', [
            'annees' => $annees,
            'cycles' => $cycles,
            'classes' => $classes,
            'eleves' => $eleves,
            'lignes' => $affichees,
            // Statistiques globales (filtres année/cycle/classe/élève)
            'totaux' => $this->calculerTotaux($base),
            // Totaux de la liste affichée (après filtre de taux)
            'totauxAffiches' => $this->calculerTotaux($affichees),
            'tranches' => $this->calculerTranches($base),
        ]);
    }
}