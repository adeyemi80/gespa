<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classe;
use App\Models\Inscription;  

class ClasseInscriptionSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run(): void

    {

        /*------------------------------------------

        --------------------------------------------

        US Classe Data

        --------------------------------------------

        --------------------------------------------*/

        $classe = Classe::create();

  

        $inscription = Inscription::create(['classe_id' => $classe->id]);

  


        /*------------------------------------------

        --------------------------------------------

        India Country Data

        --------------------------------------------

        --------------------------------------------*/

        $classe = Clase::create(['nom' => '6ème']);

  

        $inscription = Inscription::create(['classe_id' => $classe->id, 'nom' => 'BOLA Sarr']);

  

  

    }

}