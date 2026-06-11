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
      Schema::table('enseignants', function (Blueprint $table) {
    $table->string('specialite')->nullable();
    $table->string('grade')->nullable();
    $table->date('date_embauche')->nullable();
    $table->string('statut')->default('actif');

    $table->foreignId('matiere_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('cycle_id')->nullable()->constrained()->nullOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
