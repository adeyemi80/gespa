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
        Schema::create('echeances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('frais_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('annee_id')->constrained()->onDelete('cascade');
            $table->string('nom'); // Tranche 1, Trimestre 2, etc.
            $table->date('date_limite');
            $table->decimal('montant', 10, 2);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('echeances');
    }
};
