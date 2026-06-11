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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();

            // si ton appli a déjà une table annees scolaires
            $table->foreignId('annee_id')->nullable()
                  ->constrained('annees')
                  ->nullOnDelete();

            $table->foreignId('categorie_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();

            $table->decimal('montant_prevu', 20, 2);
            $table->string('periode'); // ex: "Septembre 2025", "Trimestre 1", "Année scolaire"
            $table->string('nom'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
