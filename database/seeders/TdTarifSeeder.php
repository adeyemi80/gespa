<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TdTarif;
use App\Models\Annee;

class TdTarifSeeder extends Seeder
{
    public function run()
    {
        $annee = Annee::orderByDesc('id')->first();

        $tarifs = [
            ['categorie' => 'intermediaire', 'type' => 'seance', 'montant' => 1000],
            ['categorie' => '3eme',          'type' => 'mois',   'montant' => 5000],
            ['categorie' => '3eme',          'type' => 'annee',  'montant' => 40000],
            ['categorie' => 'terminale',     'type' => 'mois',   'montant' => 8000],
            ['categorie' => 'terminale',     'type' => 'annee',  'montant' => 64000],
        ];

        foreach ($tarifs as $t) {
            TdTarif::updateOrCreate(
                ['annee_id' => $annee->id, 'categorie' => $t['categorie'], 'type' => $t['type']],
                ['montant' => $t['montant']]
            );
        }
    }
}