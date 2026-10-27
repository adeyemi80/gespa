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
    Schema::table('paiement_achats', function (Blueprint $table) {
        $table->foreignId('inscription_td_externe_id')
            ->nullable()
            ->after('inscription_id')
            ->constrained('inscriptions_td_externes')
            ->restrictOnDelete();
    });
}

public function down(): void
{
    Schema::table('paiement_achats', function (Blueprint $table) {
        $table->dropConstrainedForeignId('inscription_td_externe_id');
    });
}

};
