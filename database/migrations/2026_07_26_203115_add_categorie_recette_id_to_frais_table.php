<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frais', function (Blueprint $table) {
            if (!Schema::hasColumn('frais', 'categorie_recette_id')) {
                $table->foreignId('categorie_recette_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('categories_recettes')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('frais', function (Blueprint $table) {
            if (Schema::hasColumn('frais', 'categorie_recette_id')) {
                $table->dropConstrainedForeignId('categorie_recette_id');
            }
        });
    }
};