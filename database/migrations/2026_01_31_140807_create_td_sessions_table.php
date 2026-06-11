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
        Schema::create('td_sessions', function (Blueprint $table) {
           $table->id();
    $table->foreignId('annee_id')->constrained();
    $table->foreignId('classe_id')->constrained();
    $table->date('date_td');
    $table->timestamps();

    $table->unique(['classe_id', 'date_td']); // 1 TD / classe / jour
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('td_sessions');
    }
};
