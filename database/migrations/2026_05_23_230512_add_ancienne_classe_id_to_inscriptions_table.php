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
        Schema::table('inscriptions', function (Blueprint $table) {
           $table->foreignId('ancienne_classe_id')
                ->nullable()
                ->after('classe_id')
                ->constrained('classes')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
              $table->dropForeign(['ancienne_classe_id']);
            $table->dropColumn('ancienne_classe_id');

        });
    }
};
