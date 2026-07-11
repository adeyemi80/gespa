<?php

namespace App\Observers;

use App\Models\Versement;

class VersementObserver
{
    /**
     * Déclenché à la création ET à la modification d'un versement.
     */
    public function saved(Versement $versement): void
    {
        $this->recalculerMontant($versement);
    }

    /**
     * Déclenché à la suppression d'un versement.
     */
    public function deleted(Versement $versement): void
    {
        $this->recalculerMontant($versement);
    }

    /**
     * Recalcule le montant de l'investissement lié
     * = somme de tous ses versements.
     */
    protected function recalculerMontant(Versement $versement): void
    {
        $investissement = $versement->investissement;

        if (! $investissement) {
            return;
        }

        $total = $investissement->versements()->sum('montant');

        // updateQuietly évite de redéclencher d'éventuels observers
        // sur Investissement pour cette mise à jour interne
        $investissement->updateQuietly(['montant' => $total]);
    }
}