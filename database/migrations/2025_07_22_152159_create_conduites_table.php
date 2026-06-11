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
        Schema::create('conduites', function (Blueprint $table) {
            $table->id();
             $table->string('matricule');
            $table->foreignId('annee_id')
              ->constrained('annees')
              ->onDelete('cascade');
            $table->foreignId('classe_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');

            $table->foreignId('trimestre_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->decimal('note_conduite', 5, 2); // Exemple : 10.50

            $table->timestamps();

            $table->unique(['inscription_id', 'trimestre_id']); // empêche les doublons de conduite pour un même élève/trimestre
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conduites', function (Blueprint $table) {
        $table->dropForeign(['annee_id']);
        $table->dropColumn('annee_id');
    });
    }
};
