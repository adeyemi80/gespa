<?php
// app/Observers/PaiementObserver.php

namespace App\Observers;

use App\Models\BudgetRecette;
use App\Models\Paiement;
use App\Models\Recette;

class PaiementObserver
{
    public function created(Paiement $paiement): void
    {
        $paiement->loadMissing(['inscription', 'frais']);

        $recette = Recette::create([
            'paiement_id'          => $paiement->id,
            'inscription_id'       => $paiement->inscription_id,
            'date_paiement'        => $paiement->date_paiement,
            'montant_verse'        => $paiement->montant_verse,
            'mode_paiement'        => $paiement->mode_paiement,
            'numero_recu'          => $paiement->numero_recu,
            'categorie_recette_id' => $paiement->frais?->categorie_recette_id,
            'annee_id'             => $paiement->inscription?->annee_id,
        ]);

        $this->ajusterBudgetRealise($recette, $recette->montant_verse);
    }

    public function updated(Paiement $paiement): void
    {
        $paiement->loadMissing(['inscription', 'frais']);
        $recette = Recette::where('paiement_id', $paiement->id)->first();

        if (!$recette) {
            return;
        }

        $ancienMontant = $recette->getOriginal('montant_verse') ?? 0;

        $recette->update([
            'date_paiement'        => $paiement->date_paiement,
            'montant_verse'        => $paiement->montant_verse,
            'mode_paiement'        => $paiement->mode_paiement,
            'numero_recu'          => $paiement->numero_recu,
            'categorie_recette_id' => $paiement->frais?->categorie_recette_id,
            'annee_id'             => $paiement->inscription?->annee_id,
        ]);

        // Retire l'ancien montant, applique le nouveau
        $this->ajusterBudgetRealise($recette, -$ancienMontant);
        $this->ajusterBudgetRealise($recette, $recette->montant_verse);
    }

    public function deleted(Paiement $paiement): void
    {
        $recette = Recette::where('paiement_id', $paiement->id)->first();

        if ($recette) {
            $this->ajusterBudgetRealise($recette, -$recette->montant_verse);
        }

        Recette::where('paiement_id', $paiement->id)->delete();
    }

    /**
     * Incrémente ou décrémente montant_realise sur le budget correspondant.
     */
    private function ajusterBudgetRealise(Recette $recette, float $montant): void
    {
        if (!$recette->categorie_recette_id || !$recette->annee_id) {
            return; // pas de budget à ajuster si catégorie/année inconnues
        }

        BudgetRecette::where('categorie_id', $recette->categorie_recette_id)
            ->where('annee_id', $recette->annee_id)
            ->increment('montant_realise', $montant);
    }
}