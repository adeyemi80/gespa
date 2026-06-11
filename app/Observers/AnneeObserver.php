<?php

namespace App\Observers;

use App\Models\Annee;
use App\Models\Trimestre;

class AnneeObserver
{
    /**
     * Handle the Annee "created" event.
     */
    public function created(Annee $annee)
    {
        // Les trimestres standards
        $trimestres = [
            ['nom' => 'Premier Trimestre', 'ordre' => 1, 'periode' => 'octobre-décembre'],
            ['nom' => 'Deuxième Trimestre', 'ordre' => 2, 'periode' => 'janvier-mars'],
            ['nom' => 'Troisième Trimestre', 'ordre' => 3, 'periode' => 'avril-juin'],
        ];

        foreach ($trimestres as $t) {
            // Vérifie si le trimestre existe déjà
            $trimestre = Trimestre::firstOrCreate(
                ['ordre' => $t['ordre']], 
                ['nom' => $t['nom'], 'periode' => $t['periode']]
            );

            // Attache le trimestre à l'année via le pivot
            $annee->trimestres()->attach($trimestre->id, ['active' => true]);
        }
    }

    /**
     * Handle the Annee "updated" event.
     */
    public function updated(Annee $annee): void
    {
        //
    }

    /**
     * Handle the Annee "deleted" event.
     */
    public function deleted(Annee $annee): void
    {
        //
    }

    /**
     * Handle the Annee "restored" event.
     */
    public function restored(Annee $annee): void
    {
        //
    }

    /**
     * Handle the Annee "force deleted" event.
     */
    public function forceDeleted(Annee $annee): void
    {
        //
    }
}
