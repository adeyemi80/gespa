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
        Schema::create('frais', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // ex: Scolarité, Transport, frais annexe, frais td, séjour, Tee Shirt, Macaron, Frais d'uniforme, de sortie pédagogique, frais de Noel, transfert
            $table->text('description')->nullable();
            $table->decimal('montant', 10, 2); // montant standard
           // $table->foreignId('classe_id')->constrained()->onDelete('cascade'); // Frais spécifiques à une classe
           // $table->foreignId('annee_id')->constrained()->onDelete('cascade'); // Frais spécifiques à une année
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frais');
    }
};
