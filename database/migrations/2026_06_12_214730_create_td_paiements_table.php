<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('td_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('annee_id')->constrained('annees')->onDelete('cascade');
            //$table->enum('type', ['seance', 'mois', 'annee']);
            //$table->unsignedTinyInteger('mois')->nullable();
            //$table->foreignId('td_seance_id')->nullable()->constrained('td_seances')->onDelete('set null');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->string('reference')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('td_paiements');
    }
};