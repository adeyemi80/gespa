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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
    $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
     $table->foreignId('classe_id')->constrained()->onDelete('cascade');
    $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
    $table->foreignId('trimestre_id')->constrained()->onDelete('cascade');
    $table->foreignId('annee_id')->constrained();
    $table->float('interrogation1')->nullable(); 
     $table->float('interrogation2')->nullable(); 
      $table->float('interrogation3')->nullable(); 
    $table->float('moyenne_interro')->nullable(); // Moyenne des interros
    $table->float('devoir1')->nullable();
    $table->float('devoir2')->nullable();
     //$table->float('examen')->nullable();
    $table->float('moyenne_matiere')->nullable(); // Calculée automatiquement
    $table->text('appreciation')->nullable();
    $table->timestamps();

    $table->unique(['inscription_id', 'matiere_id', 'trimestre_id', 'annee_id'], 'note_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
