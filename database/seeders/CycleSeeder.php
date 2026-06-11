<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
 use App\Models\Cycle;

class CycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cycle::insert([
        ['nom' => 'Maternelle', 'ordre' => 1],
        ['nom' => 'Primaire', 'ordre' => 2],
        ['nom' => 'Secondaire', 'ordre' => 3],
    ]);
    }



}

