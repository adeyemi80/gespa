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
        Schema::create('annee_classe_frais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annee_id')
                  ->constrained('annees')
                  ->onDelete('cascade');

            $table->foreignId('classe_id')
                  ->constrained('classes')
                  ->onDelete('cascade');

            $table->foreignId('frais_id')
                  ->constrained('frais')
                  ->onDelete('cascade');

            $table->decimal('montant', 10, 2)->nullable();

            $table->unique([
                'annee_id',
                'classe_id',
                'frais_id'
            ], 'unique_annee_classe_frais');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annee_classe_frais');
    }
};
