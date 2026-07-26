<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets_recettes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories_recettes')->cascadeOnDelete();
            $table->foreignId('annee_id')->constrained('annees')->cascadeOnDelete();
            $table->decimal('montant_prevu', 15, 2);
            $table->decimal('montant_realise', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['categorie_id', 'annee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets_recettes');
    }
};