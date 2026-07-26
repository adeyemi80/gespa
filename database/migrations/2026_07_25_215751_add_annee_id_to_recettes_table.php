<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recettes', function (Blueprint $table) {
            if (!Schema::hasColumn('recettes', 'annee_id')) {
                $table->foreignId('annee_id')
                    ->nullable()
                    ->after('categorie_recette_id')
                    ->constrained('annees')
                    ->nullOnDelete();
            }
        });

        DB::statement('CREATE INDEX IF NOT EXISTS recettes_annee_categorie_index ON recettes (annee_id, categorie_recette_id)');
    }

    public function down(): void
    {
        Schema::table('recettes', function (Blueprint $table) {
            if (Schema::hasColumn('recettes', 'annee_id')) {
                $table->dropConstrainedForeignId('annee_id');
            }
        });

        DB::statement('DROP INDEX IF EXISTS recettes_annee_categorie_index');
    }
};