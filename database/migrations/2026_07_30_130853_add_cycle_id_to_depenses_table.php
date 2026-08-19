<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('depenses', function (Blueprint $table) {
            if (!Schema::hasColumn('depenses', 'cycle_id')) {
                $table->foreignId('cycle_id')
                    ->nullable()
                    ->after('type_depense_id')
                    ->constrained('cycles')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('depenses', function (Blueprint $table) {
            if (Schema::hasColumn('depenses', 'cycle_id')) {
                $table->dropConstrainedForeignId('cycle_id');
            }
        });
    }
};