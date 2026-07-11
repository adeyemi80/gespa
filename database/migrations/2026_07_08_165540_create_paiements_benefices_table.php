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
        Schema::create('paiements_benefices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('repartition_id')
                ->constrained('repartitions')
                ->cascadeOnDelete();

            $table->date('date_paiement');

            $table->decimal('montant', 15, 2);

            $table->string('mode_paiement')->default('Espèces');

            $table->string('reference')->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements_benefices');
    }
};