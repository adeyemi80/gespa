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
        Schema::create('repartitions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('benefice_id')
                ->constrained('benefices')
                ->cascadeOnDelete();

            $table->foreignId('investissement_id')
                ->constrained('investissements')
                ->cascadeOnDelete();

            // Pourcentage détenu dans le capital
            $table->decimal('pourcentage', 8, 4);

            // Montant du bénéfice attribué
            $table->decimal('montant', 15, 2);

            // Statut
            $table->enum('statut', [
                'en_attente',
                'partiellement_paye',
                'paye'
            ])->default('en_attente');

            $table->text('observation')->nullable();

            $table->timestamps();

            $table->unique([
                'benefice_id',
                'investissement_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repartitions');
    }
};