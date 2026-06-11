<?php

namespace App\Observers;

use App\Models\Frais;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;

class FraisObserver
{
    /**
     * Lorsqu’un frais est créé
     */
    public function created(Frais $frais)
    {
        // 🔹 récupérer la classe liée au frais
        $classeId = DB::table('classe_frais')
            ->where('frais_id', $frais->id)
            ->value('classe_id');

        // 🔹 récupérer l’année liée au frais
        $anneeId = DB::table('annee_frais')
            ->where('frais_id', $frais->id)
            ->value('annee_id');

        if (!$classeId || !$anneeId) {
            return;
        }

        // 🔹 récupérer toutes les inscriptions compatibles
        $inscriptions = Inscription::where('classe_id', $classeId)
            ->where('annee_id', $anneeId)
            ->get();

        foreach ($inscriptions as $inscription) {

            // éviter doublon
            $exists = DB::table('inscription_frais')
                ->where('inscription_id', $inscription->id)
                ->where('frais_id', $frais->id)
                ->exists();

            if ($exists) {
                continue;
            }

            // 🔹 insertion
            DB::table('inscription_frais')->insert([
                'inscription_id' => $inscription->id,
                'frais_id'       => $frais->id,
                'annee_id'       => $anneeId,
                'montant_frais'  => $frais->montant,
                'montant_paye'   => 0,
                'reste'          => $frais->montant,
                'statut'         => 'non_payé',
                'est_arriere'    => false,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
