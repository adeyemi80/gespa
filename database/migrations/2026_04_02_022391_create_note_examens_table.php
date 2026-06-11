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
        Schema::create('note_examens', function (Blueprint $table) {
            $table->id();
             $table->foreignId('participant_id')->constrained('participant_examens')->cascadeOnDelete();
    $table->foreignId('matiere_id')->constrained()->cascadeOnDelete();
    $table->decimal('note', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_examens');
    }
};
