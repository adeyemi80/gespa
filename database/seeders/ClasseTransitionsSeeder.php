<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasseTransitionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vide la table avant insert
        DB::table('classe_transitions')->truncate();

        // Insérer les transitions correctes
        DB::table('classe_transitions')->insert([
            // Collège
            ['classe_id' => 1,  'classe_superieure_id' => 2], // 6ème → 5ème
            ['classe_id' => 2,  'classe_superieure_id' => 3], // 5ème → 4ème
            ['classe_id' => 3,  'classe_superieure_id' => 4], // 4ème → 3ème

            // 3ème → 2nde (plusieurs options)
            ['classe_id' => 4,  'classe_superieure_id' => 14], // 3ème → 2ndeA
            ['classe_id' => 4,  'classe_superieure_id' => 12], // 3ème → 2ndeC
            ['classe_id' => 4,  'classe_superieure_id' => 9],  // 3ème → 2ndeD

            // 2nde → 1ère
            ['classe_id' => 14, 'classe_superieure_id' => 11], // 2ndeA → 1èreC
            ['classe_id' => 12, 'classe_superieure_id' => 10], // 2ndeC → 1èreD
            ['classe_id' => 9,  'classe_superieure_id' => 10], // 2ndeD → 1èreD

            // 1ère → Terminale
            ['classe_id' => 11, 'classe_superieure_id' => 15], // 1èreC → TleC
             ['classe_id' => 11, 'classe_superieure_id' => 16], // 1èreC → TleD
            ['classe_id' => 10, 'classe_superieure_id' => 16], // 1èreD → TleD
        ]);
    }
}