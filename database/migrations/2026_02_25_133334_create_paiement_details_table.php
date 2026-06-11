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
        Schema::create('paiement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_id')->constrained()->cascadeOnDelete();
    $table->foreignId('inscription_frais_id')->constrained()->cascadeOnDelete();

    $table->integer('montant_paye');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_details');
    }
};
