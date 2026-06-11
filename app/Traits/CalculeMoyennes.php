<?php

namespace App\Traits;

trait CalculeMoyennes
{
    /**
     * Calcule la moyenne des interrogations disponibles (non nulles)
     */
    public function calculerMoyenneInterro(?float $i1, ?float $i2, ?float $i3): ?float
    {
        $valeurs = array_filter(
            [$i1, $i2, $i3],
            fn($v) => $v !== null
        );

        if (empty($valeurs)) return null;

        return round(array_sum($valeurs) / count($valeurs), 2);
    }

    /**
     * Calcule la moyenne matière selon les valeurs disponibles
     * Priorité : moyenne_interro, devoir1, devoir2
     */
    public function calculerMoyenneMatiere(?float $moyInterro, ?float $d1, ?float $d2): ?float
    {
        $valeurs = array_filter(
            [$moyInterro, $d1, $d2],
            fn($v) => $v !== null
        );

        if (empty($valeurs)) return null;

        return round(array_sum($valeurs) / count($valeurs), 2);
    }
}