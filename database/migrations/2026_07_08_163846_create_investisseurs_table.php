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
        Schema::create('investisseurs', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('prenom')->nullable();

            $table->string('telephone', 30)->nullable();
            $table->string('email')->nullable();

            $table->text('adresse')->nullable();

            $table->string('profession')->nullable();

            $table->string('piece_identite')->nullable();
            $table->string('numero_piece')->nullable();

            $table->date('date_naissance')->nullable();

            $table->boolean('actif')->default(true);

            $table->text('observation')->nullable();

            $table->timestamps();

            $table->index('nom');
            $table->index('telephone');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investisseurs');
    }
};