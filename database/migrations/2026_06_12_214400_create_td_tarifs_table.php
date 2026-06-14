<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('td_tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annee_id')->constrained('annees')->onDelete('cascade');
            $table->string('categorie'); // intermediaire, 3eme, terminale
            $table->enum('type', ['seance', 'mois', 'annee']);
            $table->decimal('montant', 10, 2);
            $table->timestamps();

            $table->unique(['annee_id', 'categorie', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('td_tarifs');
    }
};