<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_achats', function (Blueprint $table) {

            $table->id();

            $table->string('numero_recu', 50)->index();

            $table->foreignId('inscription_id')
                ->nullable()
                ->constrained('inscriptions')
                ->nullOnDelete();

            $table->string('nom', 255);
            $table->string('prenom', 255);

            $table->string('telephone', 30)->nullable();

            $table->foreignId('frais_id')
                ->constrained('frais')
                ->cascadeOnDelete();

            $table->foreignId('categorie_recette_id')
                ->constrained('categories_recettes')
                ->restrictOnDelete();

            $table->decimal('montant', 12, 2);

            $table->string('mode_paiement', 50);

            $table->date('date_paiement');

            $table->timestamps();

            $table->index([
                'numero_recu',
                'date_paiement',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_achats');
    }
};