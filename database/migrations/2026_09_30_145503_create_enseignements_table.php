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
        Schema::create('enseignements', function (Blueprint $table) {
            $table->id();

            // Année scolaire
            $table->foreignId('annee_id')
                ->constrained('annees')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Classe
            $table->foreignId('classe_id')
                ->constrained('classes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Matière
            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Enseignant
            $table->foreignId('enseignant_id')
                ->constrained('enseignants')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Coefficient de la matière pour cette classe
            $table->decimal('coefficient', 5, 2)
                ->default(1);

            // Volume horaire total prévu
            $table->decimal('volume_horaire', 6, 2)
                ->nullable();

            // Nombre d'heures par semaine
            $table->decimal('heures_par_semaine', 4, 2)
                ->nullable();

            // Affectation active ou non
            $table->boolean('actif')
                ->default(true);

            $table->timestamps();

            /*
             * Empêche une même matière d'être affectée
             * plusieurs fois à la même classe pour la même année.
             */
            $table->unique(
                ['annee_id', 'classe_id', 'matiere_id'],
                'enseignements_annee_classe_matiere_unique'
            );

            // Index utiles pour les recherches
            $table->index(
                ['annee_id', 'enseignant_id'],
                'enseignements_annee_enseignant_index'
            );

            $table->index(
                ['annee_id', 'classe_id'],
                'enseignements_annee_classe_index'
            );

            $table->index(
                ['annee_id', 'matiere_id'],
                'enseignements_annee_matiere_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignements');
    }
};