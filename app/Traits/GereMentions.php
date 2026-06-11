<?php

namespace App\Traits;

trait GereMentions
{
    private function getMention($moyenneTrimestre = null, $moyenneAnnuelle = null, $trimestreId = null)
    {
        // ✅ 3e trimestre → moyenne annuelle
        if ($trimestreId == 3) {
            $moyenne = $moyenneAnnuelle;
        } else {
            // ✅ 1er et 2e trimestre → moyenne trimestrielle
            $moyenne = $moyenneTrimestre;
        }

        // Sécurité
        if ($moyenne === null || $moyenne === '') {
            return '-';
        }
        
        $moyenne = (float) str_replace(',', '.', $moyenne);

        // Mentions
        if ($moyenne >= 16) return 'FÉLICITATION';
        if ($moyenne >= 14) return 'TABLEAU D\'HONNEUR';
        if ($moyenne >= 12) return 'ENCOURAGEMENT';
        if ($moyenne >= 10) return 'AVERTISSEMENT';

        return 'BLAME';
    }
}