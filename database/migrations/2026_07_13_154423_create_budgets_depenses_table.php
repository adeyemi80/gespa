<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets_depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories_depenses')->cascadeOnDelete();
            $table->foreignId('annee_id')->constrained('annees')->cascadeOnDelete();
            $table->decimal('montant_alloue', 15, 2);
            $table->decimal('montant_utilise', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['categorie_id', 'annee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets_depenses');
    }
};