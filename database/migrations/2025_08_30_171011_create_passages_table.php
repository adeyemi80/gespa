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
       Schema::create('passages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('inscription_id')->constrained()->cascadeOnDelete();
    $table->foreignId('moyenne_id')->constrained()->cascadeOnDelete();
    $table->foreignId('classe_id')->constrained()->cascadeOnDelete();
    $table->foreignId('ancienne_classe_id')->constrained('classes')->cascadeOnDelete();
    $table->foreignId('nouvelle_classe_id')->constrained('classes')->cascadeOnDelete();
    $table->foreignId('annee_id')->constrained()->cascadeOnDelete();
    $table->decimal('moyenne_annuelle', 5, 2);
    $table->string('decision')->nullable(); // true = passage confirmé
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passages');
    }
};
