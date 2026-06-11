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
        Schema::create('examen_blancs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // BEPC ou BAC
        $table->foreignId('annee_id')->constrained()->cascadeOnDelete();
       // $table->foreignId('classe_id')->constrained()->cascadeOnDelete();
        $table->date('date_debut');
        $table->date('date_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_blancs');
    }
};
