<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ATTENTION : cette migration suppose que ta table "recettes" existe déjà
// (via RecetteController) et qu'elle possède une colonne "montant".
// Adapte le nom de la colonne montant dans BudgetRecetteManager si besoin.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recettes', function (Blueprint $table) {
            $table->foreignId('categorie_recette_id')
                ->nullable()
                ->after('id')
                ->constrained('categories_recettes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('recettes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('categorie_recette_id');
        });
    }
};