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
        Schema::create('classe_transitions', function (Blueprint $table) {
            $table->id();
           $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('classe_superieure_id')->constrained('classes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['classe_id', 'classe_superieure_id']); // éviter doublons
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_transitions');
    }
};
