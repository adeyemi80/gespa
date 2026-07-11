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
        Schema::create('investissements', function (Blueprint $table) {

            $table->id();

            $table->foreignId('investisseur_id')
                  ->constrained('investisseurs')
                  ->cascadeOnDelete();

            //$table->string('reference')->unique();

            $table->date('date_investissement');

            $table->decimal('montant',15,2);

            $table->string('nom');

            $table->decimal('taux',5,2)->default(0);

            $table->string('statut')->default('actif');
            
            $table->text('observation')->nullable();

            $table->timestamps();

            $table->index('date_investissement');
            $table->index('statut');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investissements');
    }
};