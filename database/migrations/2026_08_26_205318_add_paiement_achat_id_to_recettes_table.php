<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recettes', function (Blueprint $table) {
            $table->foreignId('paiement_achat_id')
                ->nullable()
                ->after('paiement_id')
                ->constrained('paiement_achats')
                ->nullOnDelete();

            $table->index('paiement_achat_id');
        });
    }

    public function down(): void
    {
        Schema::table('recettes', function (Blueprint $table) {
            $table->dropForeign([
                'paiement_achat_id'
            ]);

            $table->dropIndex([
                'paiement_achat_id'
            ]);

            $table->dropColumn('paiement_achat_id');
        });
    }
};