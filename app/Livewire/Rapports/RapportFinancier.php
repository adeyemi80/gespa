<?php

namespace App\Livewire\Rapports;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RapportFinancier extends Component
{
    public $anneeId = null;
    public $cycleId = null;
    public $classeId = null;

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
    }

    public function updatedCycleId(): void
    {
        $this->classeId = null;
    }

    /**
     * Appliquer les filtres aux inscriptions.
     */
    private function filtrerInscriptions($query)
    {
        if ($this->anneeId) {
            $query->where('i.annee_id', $this->anneeId);
        }

        if ($this->cycleId) {
            $query->where('cl.cycle_id', $this->cycleId);
        }

        if ($this->classeId) {
            $query->where('i.classe_id', $this->classeId);
        }

        return $query;
    }

    /**
     * Calcul des montants attendus par catégorie.
     *
     * Les montants attendus proviennent du pivot inscription_frais.
     */
    private function calculerMontantsAttendus()
    {
        $query = DB::table('inscription_frais as inf')
            ->join(
                'inscriptions as i',
                'i.id',
                '=',
                'inf.inscription_id'
            )
            ->join('classes as cl', 'cl.id', '=', 'i.classe_id')
            ->join('frais as f', 'f.id', '=', 'inf.frais_id')
            ->leftJoin(
                'categories_recettes as cr',
                'cr.id',
                '=',
                'f.categorie_recette_id'
            )
            ->selectRaw(
                'f.categorie_recette_id as categorie_id,
                 COALESCE(SUM(inf.montant_frais), 0) as attendu'
            )
            ->groupBy('f.categorie_recette_id');

        $this->filtrerInscriptions($query);

        return $query->get()->keyBy('categorie_id');
    }

    /**
     * Calcul des encaissements par catégorie.
     *
     * La table recettes est utilisée comme registre des encaissements.
     * Ne pas additionner ici les tables paiements et paiement_achats :
     * cela pourrait compter deux fois les mêmes opérations.
     */
    private function calculerEncaissements()
    {
        $query = DB::table('recettes as r')
            ->leftJoin(
                'inscriptions as i',
                'i.id',
                '=',
                'r.inscription_id'
            )
            ->leftJoin('classes as cl', 'cl.id', '=', 'i.classe_id')
            ->selectRaw(
                'r.categorie_recette_id as categorie_id,
                 COALESCE(SUM(r.montant_verse), 0) as encaisse'
            )
            ->where('r.annee_id', $this->anneeId)
            ->groupBy('r.categorie_recette_id');

        if ($this->cycleId) {
            $query->where('cl.cycle_id', $this->cycleId);
        }

        if ($this->classeId) {
            $query->where('i.classe_id', $this->classeId);
        }

        return $query->get()->keyBy('categorie_id');
    }

    /**
     * Données complètes du rapport.
     */
    private function calculerRapport(): array
    {
        $attendus = $this->calculerMontantsAttendus();
        $encaissements = $this->calculerEncaissements();

        $categories = DB::table('categories_recettes')
            ->where('actif', true)
            ->orderBy('id')
            ->get()
            ->map(function ($categorie) use (
                $attendus,
                $encaissements
            ) {
                $id = $categorie->id;

                $montantAttendu = (float) (
                    $attendus[$id]->attendu ?? 0
                );

                $montantEncaisse = (float) (
                    $encaissements[$id]->encaisse ?? 0
                );

                return [
                    'id' => $id,
                    'code' => $categorie->code,
                    'nom' => $categorie->nom,
                    'est_achat' => (bool) $categorie->est_achat,
                    'attendu' => $montantAttendu,
                    'encaisse' => $montantEncaisse,
                    'reste' => max(
                        0,
                        $montantAttendu - $montantEncaisse
                    ),
                ];
            });

        $scolarite = $categories->firstWhere('id', 1);
        $inscription = $categories->firstWhere('id', 2);
        $reinscription = $categories->firstWhere('id', 3);

        $attenduScolarite = (float) ($scolarite['attendu'] ?? 0);
        $encaisseScolarite = (float) ($scolarite['encaisse'] ?? 0);

        $attenduInscription =
            (float) ($inscription['attendu'] ?? 0)
            + (float) ($reinscription['attendu'] ?? 0);

        $encaisseInscription =
            (float) ($inscription['encaisse'] ?? 0)
            + (float) ($reinscription['encaisse'] ?? 0);

        $attenduGeneral = (float) $categories->sum('attendu');
        $encaisseGeneral = (float) $categories->sum('encaisse');

        return [
            'categories' => $categories,

            'attenduScolarite' => $attenduScolarite,
            'encaisseScolarite' => $encaisseScolarite,
            'resteScolarite' => max(
                0,
                $attenduScolarite - $encaisseScolarite
            ),

            'attenduInscription' => $attenduInscription,
            'encaisseInscription' => $encaisseInscription,
            'resteInscription' => max(
                0,
                $attenduInscription - $encaisseInscription
            ),

            'attenduGeneral' => $attenduGeneral,
            'encaisseGeneral' => $encaisseGeneral,
            'resteGeneral' => max(
                0,
                $attenduGeneral - $encaisseGeneral
            ),
        ];
    }

    /**
     * Export PDF selon les filtres actifs.
     */
    public function exporterPdf()
    {
        $rapport = $this->calculerRapport();

        $annee = Annee::find($this->anneeId);
        $cycle = $this->cycleId
            ? Cycle::find($this->cycleId)
            : null;
        $classe = $this->classeId
            ? Classe::find($this->classeId)
            : null;

        $pdf = Pdf::loadView(
            'livewire.rapports.pdf-financier',
            [
                'rapport' => $rapport,
                'annee' => $annee,
                'cycle' => $cycle,
                'classe' => $classe,
                'dateEdition' => now()->format('d/m/Y H:i'),
            ]
        )->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rapport-financier-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }

    public function render()
    {
        $rapport = $this->calculerRapport();

        $annees = Annee::query()
            ->orderByDesc('id')
            ->get();

        $cycles = Cycle::query()
            ->orderBy('ordre')
            ->get();

        $classesQuery = Classe::query();

        if ($this->cycleId) {
            $classesQuery->where('cycle_id', $this->cycleId);
        }

        $classes = $classesQuery
            ->orderBy('ordre')
            ->get();

        return view(
            'livewire.rapports.rapport-financier',
            compact(
                'rapport',
                'annees',
                'cycles',
                'classes'
            )
        );
    }
}