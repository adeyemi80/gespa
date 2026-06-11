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
        Schema::create('bulletins', function (Blueprint $table) {
           $table->id();
            //$table->foreignId('eleve_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained()->cascadeOnDelete();
             $table->foreignId('inscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('annee_id')->constrained()->onDelete('cascade');
            $table->foreignId('trimestre_id')->constrained()->cascadeOnDelete();
             $table->decimal('moyenne_trimestrielle', 5, 2)->nullable();
            $table->decimal('moyenne_annuelle', 5, 2)->nullable();
            $table->decimal('moyenne_scientifique', 5, 2)->nullable();
            $table->decimal('moyenne_litteraire', 5, 2)->nullable();
            $table->string('rang_trimestre', 10)->nullable();
            $table->string('rang_annuel', 10)->nullable();
            $table->timestamps();

            // Un bulletin par élève, classe, année et trimestre
            $table->unique(['eleve_id', 'classe_id', 'annee_id', 'trimestre_id', 'moyenne_id'], 'unique_bulletin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
