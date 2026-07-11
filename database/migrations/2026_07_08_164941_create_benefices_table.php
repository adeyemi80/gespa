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
        Schema::create('benefices', function (Blueprint $table) {

            $table->id();

            $table->date('date_debut');

            $table->date('date_fin');

            $table->decimal('montant', 15, 2);

            $table->text('observation')->nullable();

            $table->timestamps();

            $table->index('date_debut');
            $table->index('date_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefices');
    }
};