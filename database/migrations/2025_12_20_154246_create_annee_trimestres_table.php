<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annee_trimestre', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('annee_id')
                  ->constrained('annees')
                  ->cascadeOnDelete();

            $table->foreignId('trimestre_id')
                  ->constrained('trimestres')
                  ->cascadeOnDelete();
          $table->boolean('active')->default(true);
            $table->timestamps();

            // Empêcher les doublons année + trimestre
            $table->unique(['annee_id', 'trimestre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annee_trimestre');
    }
};
