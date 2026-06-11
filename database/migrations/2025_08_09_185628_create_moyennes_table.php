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
        Schema::create('moyennes', function (Blueprint $table) {
             $table->id();
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
              $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('trimestre_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_id')->constrained()->onDelete('cascade');
            $table->decimal('moyenne_trimestrielle', 5, 2)->nullable();
            $table->decimal('moyenne_annuelle', 5, 2)->nullable();
            $table->decimal('moyenne_scientifique', 5, 2)->nullable();
            $table->decimal('moyenne_litteraire', 5, 2)->nullable();
            $table->string('rang_trimestre', 10)->nullable();
            $table->string('rang_annuel', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('moyennes', function (Blueprint $table) {
            $table->dropForeign(['eleve_id']);
            $table->dropColumn('eleve_id');
        });
    }
};
