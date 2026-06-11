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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('titre'); // Nom du test
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade'); // Matière du test
             $table->foreignId('trimestre_id')->constrained()->onDelete('cascade');
           $table->enum('type', [
  'interrogation1',
  'interrogation2',
  'interrogation3',
  'devoir1',
  'devoir2',
  'examen'
]);
 // Type de test
            $table->foreignId('classe_id')->constrained()->onDelete('cascade'); // Classe concernée
            $table->foreignId('annee_id')->constrained()->onDelete('cascade'); // Année scolaire
            $table->string('fichier')->nullable(); // Fichier du test
            $table->date('date');                 // date du test
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
