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
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('niveau', 50); // ex: 6eme
            $table->unsignedInteger('coefficient')->default(1);
            $table->string('type')->default('scientifique'); // ou 'litteraire'
            $table->foreignId('enseignant_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->unique(['nom', 'niveau']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('matieres', function (Blueprint $table) {
            $table->dropUnique('matieres_nom_classe_unique');
        });
    }
};
