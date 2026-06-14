<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TYPE td_categorie AS ENUM (
                'intermediaire',
                '3eme',
                'terminale'
            )
        ");

        DB::statement("
            ALTER TABLE td_tarifs
            ALTER COLUMN categorie
            TYPE td_categorie
            USING categorie::td_categorie
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE td_tarifs
            ALTER COLUMN categorie
            TYPE VARCHAR(255)
        ");

        DB::statement("
            DROP TYPE IF EXISTS td_categorie
        ");
    }
};