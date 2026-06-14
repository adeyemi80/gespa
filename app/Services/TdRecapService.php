<?php

namespace App\Services;

use App\Models\Eleve;
use App\Models\Classe;
use App\Models\TdPresence;
use App\Models\TdSeance;
use App\Models\TdTarif;
use App\Models\TdPaiement;
use Carbon\Carbon;

class TdRecapService
{
    /**
     * Mois scolaires (Octobre → Mai)
     */
    public const MOIS_SCOLAIRES = [10, 11, 12, 1, 2, 3, 4, 5];

    public const NB_MOIS_ANNEE = 8;

    /* =========================================================
     |  OUTILS DE BASE
     ========================================================= */

    public function categorieClasse(string $niveau): string
    {
        $niveau = strtolower(trim($niveau));

        if (str_contains($niveau, '3eme') || str_contains($niveau, '3ème')) {
            return '3eme';
        }

        if (
            str_contains($niveau, 'tle') ||
            str_contains($niveau, 'terminale')
        ) {
            return 'terminale';
        }

        return 'intermediaire';
    }

    public function getTarif(int $anneeId, string $categorie, string $type): float
    {
        return TdTarif::where('annee_id', $anneeId)
            ->where('categorie', $categorie)
            ->where('type', $type)
            ->value('montant') ?? 0;
    }

    public function anneeCivilePourMois(int $mois, int $anneeDebut): int
    {
        return in_array($mois, [10, 11, 12])
            ? $anneeDebut
            : $anneeDebut + 1;
    }

    public function moisEcoules(int $mois): array
    {
        $index = array_search($mois, self::MOIS_SCOLAIRES);

        return $index === false
            ? self::MOIS_SCOLAIRES
            : array_slice(self::MOIS_SCOLAIRES, 0, $index + 1);
    }

    /* =========================================================
     |  PRÉSENCES / SÉANCES
     ========================================================= */

    private function seancesIdsDuMois(int $anneeId, int $mois, int $anneeCivile)
    {
        return TdSeance::where('annee_id', $anneeId)
            ->whereMonth('date', $mois)
            ->whereYear('date', $anneeCivile)
            ->pluck('id');
    }

    private function nbSeancesSuivies(int $eleveId, int $anneeId, int $mois, int $anneeCivile): int
    {
        return TdPresence::where('eleve_id', $eleveId)
            ->whereIn('td_seance_id', $this->seancesIdsDuMois($anneeId, $mois, $anneeCivile))
            ->where('present', true)
            ->count();
    }

    /* =========================================================
     |  DETTE (DÛ)
     ========================================================= */

    private function montantDuMois(
        int $eleveId,
        int $anneeId,
        string $categorie,
        int $mois,
        int $anneeCivile
    ): float {

        $nbSeances = $this->nbSeancesSuivies(
            $eleveId,
            $anneeId,
            $mois,
            $anneeCivile
        );

        // INTERMÉDIAIRE : paiement à la séance
        if ($categorie === 'intermediaire') {
            return $nbSeances * $this->getTarif($anneeId, $categorie, 'seance');
        }

        // 3ème / Terminale : mois dû si au moins 1 présence
        if (in_array($categorie, ['3eme', 'terminale'])) {
            return $nbSeances > 0
                ? $this->getTarif($anneeId, $categorie, 'mois')
                : 0;
        }

        return 0;
    }

    /**
     * DÛ CUMULÉ JUSQU'AU MOIS (inclus)
     */
    private function montantDuCumule(
        int $eleveId,
        int $anneeId,
        string $categorie,
        array $moisEcoules,
        int $anneeDebut
    ): float {

        $total = 0;

        foreach ($moisEcoules as $mois) {

            $anneeCivile = $this->anneeCivilePourMois($mois, $anneeDebut);

            $total += $this->montantDuMois(
                $eleveId,
                $anneeId,
                $categorie,
                $mois,
                $anneeCivile
            );
        }

        return $total;
    }

    /* =========================================================
     |  PAYÉ - CUMUL JUSQU'À UN MOIS DONNÉ (CORRECTION 1)
     ========================================================= */

    /**
     * Total des paiements effectués jusqu'à la fin du mois scolaire donné
     * (inclus), peu importe le type de paiement.
     */
    private function montantPayeJusquAuMois(
        int $eleveId,
        int $anneeId,
        int $mois,
        int $anneeDebut
    ): float {

        $anneeCivile = $this->anneeCivilePourMois($mois, $anneeDebut);

        $dateFin = Carbon::create($anneeCivile, $mois, 1)->endOfMonth();

        return TdPaiement::where('eleve_id', $eleveId)
            ->where('annee_id', $anneeId)
            ->whereDate('date_paiement', '<=', $dateFin)
            ->sum('montant');
    }

    /**
     * Total des paiements sur toute l'année (utilisé pour le récap annuel).
     */
    private function montantPayeTotal(int $eleveId, int $anneeId): float
    {
        return TdPaiement::where('eleve_id', $eleveId)
            ->where('annee_id', $anneeId)
            ->sum('montant');
    }

    /* =========================================================
     |  RECAP MENSUEL (COMPTE GLOBAL)
     ========================================================= */

    public function recapMensuel(
        Eleve $eleve,
        Classe $classe,
        int $anneeId,
        int $mois,
        int $anneeDebut
    ): array {

        $categorie = $this->categorieClasse($classe->niveau);

        $moisEcoules    = $this->moisEcoules($mois);
        $moisPrecedents = array_slice($moisEcoules, 0, -1);

        $anneeCiviledMois = $this->anneeCivilePourMois($mois, $anneeDebut);

        // =========================
        // DÛ DU MOIS SÉLECTIONNÉ
        // =========================
        $montantDuMois = $this->montantDuMois(
            $eleve->id,
            $anneeId,
            $categorie,
            $mois,
            $anneeCiviledMois
        );

        // =========================
        // DÛ CUMULÉ (jusqu'à ce mois inclus)
        // =========================
        $montantDuCumule = $this->montantDuCumule(
            $eleve->id,
            $anneeId,
            $categorie,
            $moisEcoules,
            $anneeDebut
        );

        // =========================
        // PAYÉ CUMULÉ (jusqu'à ce mois inclus) — CORRECTION 1
        // =========================
        $montantPayeCumule = $this->montantPayeJusquAuMois(
            $eleve->id,
            $anneeId,
            $mois,
            $anneeDebut
        );

        // =========================
        // ARRIÉRÉ AVANT CE MOIS — CORRECTION 2
        // =========================
        if (empty($moisPrecedents)) {
            // Premier mois de l'année scolaire : aucun arriéré possible
            $arriere = 0;
        } else {
            $moisDuDernierPrecedent = end($moisPrecedents);

            $dettePrecedente = $this->montantDuCumule(
                $eleve->id,
                $anneeId,
                $categorie,
                $moisPrecedents,
                $anneeDebut
            );

            $payePrecedent = $this->montantPayeJusquAuMois(
                $eleve->id,
                $anneeId,
                $moisDuDernierPrecedent,
                $anneeDebut
            );

            $arriere = max($dettePrecedente - $payePrecedent, 0);
        }

        return [
            'eleve'  => $eleve,
            'classe' => $classe,
            'mode_paiement' => 'global',

            'arriere_avant_ce_mois' => $arriere,

            'montant_du_mois'   => $montantDuMois,
            'montant_du_cumule' => $montantDuCumule,

            'montant_paye_cumule'  => $montantPayeCumule,
            'reste_a_payer_cumule' => max($montantDuCumule - $montantPayeCumule, 0),

            'avance' => max($montantPayeCumule - $montantDuCumule, 0),

            // info utile UI
            'mois_jusqu_ici' => $moisEcoules,
        ];
    }

    /* =========================================================
     |  RECAP ANNUEL — CORRECTION 3
     ========================================================= */

    public function recapAnnuel(
        Eleve $eleve,
        Classe $classe,
        int $anneeId,
        int $anneeDebut
    ): array {

        $categorie = $this->categorieClasse($classe->niveau);

        if ($categorie === 'intermediaire') {

            $seancesIds = TdSeance::where('annee_id', $anneeId)->pluck('id');

            $nbSeances = TdPresence::where('eleve_id', $eleve->id)
                ->whereIn('td_seance_id', $seancesIds)
                ->where('present', true)
                ->count();

            $montantDu = $nbSeances * $this->getTarif($anneeId, $categorie, 'seance');

        } else {

            // 3eme / terminale : un mois commencé = un mois dû
            $montantDu = 0;

            foreach (self::MOIS_SCOLAIRES as $mois) {

                $anneeCivile = $this->anneeCivilePourMois($mois, $anneeDebut);

                $nb = $this->nbSeancesSuivies(
                    $eleve->id,
                    $anneeId,
                    $mois,
                    $anneeCivile
                );

                if ($nb > 0) {
                    $montantDu += $this->getTarif($anneeId, $categorie, 'mois');
                }
            }
        }

        $montantPaye = $this->montantPayeTotal($eleve->id, $anneeId);

        return [
            'eleve'  => $eleve,
            'classe' => $classe,

            'montant_du'    => $montantDu,
            'montant_paye'  => $montantPaye,
            'reste_a_payer' => max($montantDu - $montantPaye, 0),
        ];
    }
}