<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annee_frais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('frais_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['annee_id', 'frais_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annee_frais');
    }
};
