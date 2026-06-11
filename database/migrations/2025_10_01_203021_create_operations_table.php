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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // date de la dépense
            $table->string('libelle'); // description (achat matériel, salaires, etc.)
            $table->decimal('montant', 20, 2); // montant dépensé
            $table->string('description')->nullable(); 
            $table->enum('categorie', ['recette', 'dépense']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
