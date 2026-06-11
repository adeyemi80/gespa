<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('moyennes', function (Blueprint $table) {
            if (!Schema::hasColumn('moyennes', 'notes')) {
                $table->json('notes')->nullable()->after('inscription_id');
            }
            if (!Schema::hasColumn('moyennes', 'note_conduite')) {
                $table->decimal('note_conduite', 5, 2)->nullable()->after('notes');
            }
            if (!Schema::hasColumn('moyennes', 'appreciation_conduite')) {
                $table->string('appreciation_conduite', 255)->nullable()->after('note_conduite');
            }
            if (!Schema::hasColumn('moyennes', 'appreciation')) {
                $table->string('appreciation', 255)->nullable()->after('mention');
            }
            if (!Schema::hasColumn('moyennes', 'total_eleves')) {
                $table->integer('total_eleves')->nullable()->after('appreciation');
            }
            if (!Schema::hasColumn('moyennes', 'plus_faible_moyenne')) {
                $table->decimal('plus_faible_moyenne', 5, 2)->nullable()->after('total_eleves');
            }
            if (!Schema::hasColumn('moyennes', 'plus_forte_moyenne')) {
                $table->decimal('plus_forte_moyenne', 5, 2)->nullable()->after('plus_faible_moyenne');
            }
            if (!Schema::hasColumn('moyennes', 'moyenne_t1')) {
                $table->decimal('moyenne_t1', 5, 2)->nullable()->after('plus_forte_moyenne');
            }
            if (!Schema::hasColumn('moyennes', 'moyenne_t2')) {
                $table->decimal('moyenne_t2', 5, 2)->nullable()->after('moyenne_t1');
            }
            if (!Schema::hasColumn('moyennes', 'moyenne_t3')) {
                $table->decimal('moyenne_t3', 5, 2)->nullable()->after('moyenne_t2');
            }
            if (!Schema::hasColumn('moyennes', 'decision')) {
                $table->string('decision', 100)->nullable()->after('moyenne_t3');
            }
        });
    }

    public function down()
    {
        Schema::table('moyennes', function (Blueprint $table) {
            $table->dropColumn([
                'notes', 'note_conduite', 'appreciation_conduite', 'appreciation',
                'total_eleves', 'plus_faible_moyenne', 'plus_forte_moyenne',
                'moyenne_t1', 'moyenne_t2', 'moyenne_t3', 'decision'
            ]);
        });
    }
};