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
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance')->nullable();
             $table->string('numEducMaster')->nullable();
            $table->enum('sexe', ['M', 'F']);
            $table->string('nationalite');
            $table->foreignId('paren_id')
                ->constrained('parens')
                ->onDelete('cascade');
            $table->string('lieu_naissance');
            $table->string('matricule');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->enum('statut', ['passant', 'redoublant']);
            $table->foreignId('annee_id')->constrained()->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->timestamps();
             //$table->decimal('moyenne_annuelle', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
