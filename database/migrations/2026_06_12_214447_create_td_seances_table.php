<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('td_seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annee_id')->constrained('annees')->onDelete('cascade');
            $table->foreignId('classe_id');
            $table->date('date');
            $table->string('libelle')->nullable();
            $table->timestamps();

            $table->unique(['annee_id', 'classe_id', 'date'], 'td_seances_annee_classe_date_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('td_seances');
    }
};
