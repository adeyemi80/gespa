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
        Schema::create('td_paiements', function (Blueprint $table) {
             $table->id();
    $table->foreignId('td_participation_id')->constrained()->cascadeOnDelete();
    $table->decimal('montant', 10, 2);
    $table->boolean('paye')->default(false);
    $table->enum('type_frais', ['seance', 'mois', 'annee']);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('td_paiements');
    }
};
