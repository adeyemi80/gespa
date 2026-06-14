<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('td_presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('td_seance_id')->constrained('td_seances')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->boolean('present')->default(false);
            $table->timestamps();

            $table->unique(['td_seance_id', 'eleve_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('td_presences');
    }
};