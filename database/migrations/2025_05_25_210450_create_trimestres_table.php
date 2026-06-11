<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


    /**
     * Run the migrations.
     */
   return new class extends Migration {
    public function up(): void
    {
       Schema::create('trimestres', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('nom');
    $table->integer('ordre'); // 1, 2, 3
    $table->string('periode')->nullable();

    $table->timestamps();

    
});
    }

    public function down(): void
    {
        Schema::table('trimestres', function (Blueprint $table) {
            $table->dropUnique('trimestres_nom_annee_unique');
        });
    }
};



