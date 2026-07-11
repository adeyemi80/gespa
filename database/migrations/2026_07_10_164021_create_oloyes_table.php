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
        Schema::create('oloyes', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            $table->string('libelle');

            $table->string('categorie')->nullable();

            $table->decimal('montant', 15, 2);

            $table->string('beneficiaire')->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oloyes');
    }
};