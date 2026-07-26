<?php
// app/Observers/PaiementObserver.php

namespace App\Observers;

use App\Models\Paiement;
use App\Models\Recette;

class PaiementObserver
{
    public function created(Paiement $paiement): void
    {
        $paiement->loadMissing(['inscription', 'frais']);

        Recette::create([
            'paiement_id'          => $paiement->id,
            'inscription_id'       => $paiement->inscription_id,
            'date_paiement'        => $paiement->date_paiement,
            'montant_verse'        => $paiement->montant_verse,
            'mode_paiement'        => $paiement->mode_paiement,
            'numero_recu'          => $paiement->numero_recu,
            'categorie_recette_id' => $paiement->frais?->categorie_recette_id,
            'annee_id'             => $paiement->inscription?->annee_id,
        ]);
    }

    public function updated(Paiement $paiement): void
    {
        $paiement->loadMissing(['inscription', 'frais']);

        Recette::where('paiement_id', $paiement->id)->update([
            'date_paiement'        => $paiement->date_paiement,
            'montant_verse'        => $paiement->montant_verse,
            'mode_paiement'        => $paiement->mode_paiement,
            'numero_recu'          => $paiement->numero_recu,
            'categorie_recette_id' => $paiement->frais?->categorie_recette_id,
            'annee_id'             => $paiement->inscription?->annee_id,
        ]);
    }

    public function deleted(Paiement $paiement): void
    {
        Recette::where('paiement_id', $paiement->id)->delete();
    }
}