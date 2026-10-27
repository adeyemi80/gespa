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
    Schema::create('inscriptions_td_externes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('eleve_externe_td_id')
            ->constrained('eleves_externes_td')
            ->restrictOnDelete();

        $table->foreignId('annee_id')
            ->constrained('annees')
            ->restrictOnDelete();

        $table->foreignId('classe_id')
            ->constrained('classes')
            ->restrictOnDelete();

        $table->foreignId('frais_id')
            ->constrained('frais')
            ->restrictOnDelete();

        $table->date('date_inscription');

        // Montant convenu lors de l'inscription.
        $table->decimal('montant_total', 12, 2);

        $table->string('statut')->default('active');

        $table->text('observations')->nullable();

        $table->timestamps();

        $table->unique(
            ['eleve_externe_td_id', 'annee_id'],
            'inscription_td_externe_annee_unique'
        );
    });
}

public function down(): void
{
    Schema::dropIfExists('inscriptions_td_externes');
}

    /**
     * Reverse the migrations.
     */
    
};
