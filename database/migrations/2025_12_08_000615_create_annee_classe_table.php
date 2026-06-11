<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('classe_annee', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers la table classes
            $table->foreignId('classe_id')
                  ->constrained('classes')
                  ->cascadeOnDelete();

            // Clé étrangère vers la table annees
            $table->foreignId('annee_id')
                  ->constrained('annees')
                  ->cascadeOnDelete();

            $table->boolean('active')->default(true);

            $table->timestamps();

            // Empêche les doublons (une classe ne peut pas être liée deux fois à la même année)
            $table->unique(['classe_id', 'annee_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('classe_annee');
    }
};
