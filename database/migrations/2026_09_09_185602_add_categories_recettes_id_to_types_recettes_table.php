<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types_recettes', function (Blueprint $table) {
            $table->foreignId('categories_recettes_id')
                ->after('id')
                ->constrained('categories_recettes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('types_recettes', function (Blueprint $table) {
            $table->dropForeign([
                'categories_recettes_id'
            ]);

            $table->dropColumn('categories_recettes_id');
        });
    }
};