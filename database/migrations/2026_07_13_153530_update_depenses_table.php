<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Renommer la colonne date -> date_depense
        if (
            Schema::hasColumn('depenses', 'date') &&
            !Schema::hasColumn('depenses', 'date_depense')
        ) {
            Schema::table('depenses', function (Blueprint $table) {
                $table->renameColumn('date', 'date_depense');
            });
        }

        Schema::table('depenses', function (Blueprint $table) {

            if (!Schema::hasColumn('depenses', 'numero_piece')) {
                $table->string('numero_piece')->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('depenses', 'type_depense_id')) {
                $table->foreignId('type_depense_id')
                    ->nullable()
                    ->after('numero_piece')
                    ->constrained('types_depenses')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('depenses', 'annee_id')) {
                $table->foreignId('annee_id')
                    ->nullable()
                    ->after('type_depense_id')
                    ->constrained('annees')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('depenses', 'beneficiaire')) {
                $table->string('beneficiaire')->nullable()->after('date_depense');
            }

            if (!Schema::hasColumn('depenses', 'mode_paiement')) {
                $table->string('mode_paiement')
                    ->default('especes')
                    ->after('beneficiaire');
            }

            if (!Schema::hasColumn('depenses', 'reference_paiement')) {
                $table->string('reference_paiement')
                    ->nullable()
                    ->after('mode_paiement');
            }

            if (!Schema::hasColumn('depenses', 'statut')) {
                $table->string('statut')
                    ->default('brouillon')
                    ->after('reference_paiement');
            }

            if (!Schema::hasColumn('depenses', 'motif_rejet')) {
                $table->text('motif_rejet')
                    ->nullable()
                    ->after('statut');
            }

            if (!Schema::hasColumn('depenses', 'cree_par')) {
                $table->foreignId('cree_par')
                    ->nullable()
                    ->after('motif_rejet')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('depenses', 'valide_par')) {
                $table->foreignId('valide_par')
                    ->nullable()
                    ->after('cree_par')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('depenses', 'valide_le')) {
                $table->timestamp('valide_le')
                    ->nullable()
                    ->after('valide_par');
            }
        });

        // Création des index (si absents)
        DB::statement('CREATE INDEX IF NOT EXISTS depenses_annee_statut_index ON depenses (annee_id, statut)');
        DB::statement('CREATE INDEX IF NOT EXISTS depenses_date_depense_index ON depenses (date_depense)');
    }

    public function down(): void
    {
        Schema::table('depenses', function (Blueprint $table) {

            if (Schema::hasColumn('depenses', 'valide_le')) {
                $table->dropColumn('valide_le');
            }

            if (Schema::hasColumn('depenses', 'valide_par')) {
                $table->dropConstrainedForeignId('valide_par');
            }

            if (Schema::hasColumn('depenses', 'cree_par')) {
                $table->dropConstrainedForeignId('cree_par');
            }

            if (Schema::hasColumn('depenses', 'motif_rejet')) {
                $table->dropColumn('motif_rejet');
            }

            if (Schema::hasColumn('depenses', 'statut')) {
                $table->dropColumn('statut');
            }

            if (Schema::hasColumn('depenses', 'reference_paiement')) {
                $table->dropColumn('reference_paiement');
            }

            if (Schema::hasColumn('depenses', 'mode_paiement')) {
                $table->dropColumn('mode_paiement');
            }

            if (Schema::hasColumn('depenses', 'beneficiaire')) {
                $table->dropColumn('beneficiaire');
            }

            if (Schema::hasColumn('depenses', 'annee_id')) {
                $table->dropConstrainedForeignId('annee_id');
            }

            if (Schema::hasColumn('depenses', 'type_depense_id')) {
                $table->dropConstrainedForeignId('type_depense_id');
            }

            if (Schema::hasColumn('depenses', 'numero_piece')) {
                $table->dropUnique(['numero_piece']);
                $table->dropColumn('numero_piece');
            }
        });

        if (
            Schema::hasColumn('depenses', 'date_depense') &&
            !Schema::hasColumn('depenses', 'date')
        ) {
            Schema::table('depenses', function (Blueprint $table) {
                $table->renameColumn('date_depense', 'date');
            });
        }

        DB::statement('DROP INDEX IF EXISTS depenses_annee_statut_index');
        DB::statement('DROP INDEX IF EXISTS depenses_date_depense_index');
    }
};