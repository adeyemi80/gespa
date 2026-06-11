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
        //Schema::disableForeignKeyConstraints();
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100); // ex: 6ème A
            $table->string('niveau', 50); // ex: 6eme
            $table->foreignId('classe_superieure_id')->nullable()->constrained('classes')->nullOnDelete();
            // Activation globale de la classe
            $table->timestamps();
            $table->unique(['nom', 'niveau']);        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropUnique('classes_nom_annee_unique');
        });
    }
};
