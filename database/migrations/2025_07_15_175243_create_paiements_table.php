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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('frais_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_id')->constrained()->onDelete('cascade');
              $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->date('date_paiement')->nullable()->default(DB::raw('CURRENT_DATE'));
            $table->decimal('montant_verse', 10, 2);
            $table->string('mode_paiement'); // Espèces, mobile money, chèque
            $table->string('numero_recu')->nullable(); // numéro ou référence du reçu
             $table->integer('montant_total');
    $table->string('reference')->unique();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
