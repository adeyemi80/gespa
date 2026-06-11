<?php

namespace App\Observers;

use App\Models\Inscription;
use Illuminate\Support\Facades\DB;

class InscriptionObserver
{
    public function created(Inscription $inscription)
    {
        // 🔹 Récupérer les frais de la classe
        $fraisClasse = DB::table('annee_classe_frais')
    ->join('frais', 'annee_classe_frais.frais_id', '=', 'frais.id')
    ->where('annee_classe_frais.classe_id', $inscription->classe_id)
    ->where('annee_classe_frais.annee_id', $inscription->annee_id)
    ->select(
        'frais.id',
        'frais.nom',
        'annee_classe_frais.montant'
    )
    ->get();

        foreach ($fraisClasse as $frais) {

            // 🔒 Protection anti-doublon
            $existe = DB::table('inscription_frais')
                ->where('inscription_id', $inscription->id)
                ->where('frais_id', $frais->id)
                ->where('annee_id', $inscription->annee_id)
                ->exists();

            if ($existe) {
                continue;
            }

            DB::table('inscription_frais')->insert([
                'inscription_id' => $inscription->id,
                'frais_id'       => $frais->id,
                'annee_id'       => $inscription->annee_id,
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
