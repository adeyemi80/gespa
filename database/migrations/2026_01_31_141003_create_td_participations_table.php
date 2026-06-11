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
        Schema::create('td_participations', function (Blueprint $table) {
             $table->id();
    $table->foreignId('td_session_id')->constrained()->cascadeOnDelete();
    $table->foreignId('inscription_id')->constrained();
    $table->boolean('a_participe')->default(true);
    $table->timestamps();

    $table->unique(['td_session_id', 'inscription_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('td_participations');
    }
};
