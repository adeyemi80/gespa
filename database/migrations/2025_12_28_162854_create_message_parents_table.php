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
        Schema::create('message_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')
        ->constrained('eleves')
        ->cascadeOnDelete();

    $table->foreignId('paren_id')
        ->constrained('parens')
        ->cascadeOnDelete();

    $table->foreignId('user_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete(); // enseignant / admin

    $table->string('objet');
    $table->text('message');

    $table->enum('type', ['info', 'avertissement', 'felicitation'])
          ->default('info');

    $table->boolean('lu')->default(false);

    $table->timestamps();

    $table->index(['paren_id', 'lu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_parents');
    }
};
