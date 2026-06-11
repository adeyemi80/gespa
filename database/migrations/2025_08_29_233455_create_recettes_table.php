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
        Schema::create('recettes', function (Blueprint $table) {
             $table->id();
            $table->date('date_paiement'); // date de la recette
            $table->decimal('montant_verse', 20, 2);  // montant reçu
            $table->foreignId('paiement_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
             $table->string('mode_paiement'); // Espèces, mobile money, chèque
              $table->string('numero_recu')->nullable(); // numéro ou référence du reçu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recettes');
    }
};
