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
        Schema::create('parametres_investissements', function (Blueprint $table) {

            $table->id();

            $table->string('cle')->unique();

            $table->string('libelle');

            $table->text('valeur')->nullable();

            $table->string('type')->default('texte');

            $table->text('description')->nullable();

            $table->boolean('actif')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametres_investissements');
    }
};