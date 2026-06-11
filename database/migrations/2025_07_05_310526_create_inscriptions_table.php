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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('eleve_id')
                ->constrained('eleves')
                ->onDelete('cascade');
            $table->foreignId('classe_id')
                ->constrained('classes')
                ->onDelete('cascade');
            $table->foreignId('annee_id')
                ->constrained('annees')
                ->onDelete('cascade');
                $table->decimal('moyenne_annuelle', 5, 2)->nullable();
           $table->enum('decision', ['passé', 'redoublé'])->nullable();
         $table->date('date_inscription')->nullable()->default(DB::raw('CURRENT_DATE'));
         $table->unique(['eleve_id', 'annee_id']);
            $table->timestamps();
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
          $table->dropColumn(['moyenne_obtenue', 'decision']);
    }
};
