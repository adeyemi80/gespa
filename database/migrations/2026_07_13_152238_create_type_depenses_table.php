<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories_depenses')->cascadeOnDelete();
            //$table->string('code', 15)->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_depenses');
    }
};