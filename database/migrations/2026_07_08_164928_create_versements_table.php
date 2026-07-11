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
        Schema::create('versements', function (Blueprint $table) {

            $table->id();

            $table->foreignId('investissement_id')
                ->constrained('investissements')
                ->cascadeOnDelete();

            $table->date('date_versement');

            $table->decimal('montant', 15, 2);

            $table->string('mode_paiement', 50);

            $table->string('reference')->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();

            $table->index('date_versement');
            $table->index('mode_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};