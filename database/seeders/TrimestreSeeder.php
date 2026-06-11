<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrimestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    $trimestres = [
        ['nom' => 'Premier Trimestre',   'ordre' => 1, 'periode' => 'octobre-décembre'],
        ['nom' => 'Deuxième Trimestre',  'ordre' => 2, 'periode' => 'janvier-mars'],
        ['nom' => 'Troisième Trimestre', 'ordre' => 3, 'periode' => 'avril-juin'],
    ];

    foreach ($trimestres as $t) {
        Trimestre::updateOrCreate(
            ['ordre' => $t['ordre']],
            $t
        );
    }
}

}
