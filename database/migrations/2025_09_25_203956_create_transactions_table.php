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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
             $table->date('date_transaction');
            $table->enum('type', ['recette', 'dépense']);
            
            $table->foreignId('categorie_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();

            $table->foreignId('compte_id')
                  ->constrained('comptes')
                  ->cascadeOnDelete();

            $table->decimal('montant', 20, 2);
            $table->string('mode_paiement')->nullable();
            $table->text('description')->nullable();

            // Utilisateur qui a saisi la transaction (si Jetstream/Auth)
            $table->foreignId('created_by')->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
