<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories_recettes', function (Blueprint $table) {
            $table->boolean('est_achat')
                ->default(false)
                ->after('actif');
        });
    }

    public function down(): void
    {
        Schema::table('categories_recettes', function (Blueprint $table) {
            $table->dropColumn('est_achat');
        });
    }
};