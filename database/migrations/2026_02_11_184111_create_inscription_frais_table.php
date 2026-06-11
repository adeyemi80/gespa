<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscription_frais', function (Blueprint $table) {
            $table->id();
            
            // Lien vers l'inscription
            $table->foreignId('inscription_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Lien vers le frais
            $table->foreignId('frais_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Lien vers l'année scolaire
            $table->foreignId('annee_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->decimal('montant_frais', 15, 2); // montant total du frais
             $table->integer('montant_total');
            $table->decimal('montant_paye', 15, 2)->default(0); // montant déjà payé
            $table->decimal('reste', 15, 2)->default(0); // reste à payer

            $table->enum('statut', ['non_payé', 'partiellement_payé', 'soldé'])->default('non_payé');

            $table->boolean('est_arriere')->default(false); // pour gérer les arriérés éventuels

            $table->timestamps();

            // Index pour accélérer les recherches
            $table->index(['inscription_id', 'frais_id', 'annee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscription_frais');
    }
};
