<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('eleves_externes_td', function (Blueprint $table) {
        $table->id();

        $table->string('matricule')->unique();
        $table->string('nom');
        $table->string('prenom');
        $table->string('sexe', 10)->nullable();

        $table->date('date_naissance')->nullable();
        $table->string('telephone')->nullable();
        $table->string('telephone_parent')->nullable();
        $table->string('adresse')->nullable();

        $table->text('observations')->nullable();
        $table->boolean('actif')->default(true);

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('eleves_externes_td');
}

    /**
     * Reverse the migrations.
     */
   
};
