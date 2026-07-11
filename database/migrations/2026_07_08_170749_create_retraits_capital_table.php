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
        Schema::create('retraits_capital', function (Blueprint $table) {

            $table->id();

            $table->foreignId('investissement_id')
                ->constrained('investissements')
                ->cascadeOnDelete();

            $table->date('date_retrait');

            $table->decimal('montant', 15, 2);

            $table->string('mode_retrait')->nullable();

            $table->string('reference')->nullable();

            $table->text('motif')->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retraits_capital');
    }
};