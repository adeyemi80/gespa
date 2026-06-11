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
        Schema::create('annees', function (Blueprint $table) {
           $table->id();
            $table->string('nom')->unique(); // ex: 2024-2025
            $table->date('debut')->nullable();
            $table->date('fin')->nullable();
            $table->boolean('en_cours')->default(false); // année active ?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annees');
    }
};
