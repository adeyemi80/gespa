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
        Schema::create('contrats_prestataires', function (Blueprint $table) {
            $table->id();
            $table->string('etablissement');
$table->text('adresse_etablissement');
$table->string('representant');
$table->string('fonction');
$table->string('prestataire_nom');
$table->text('prestataire_adresse');
$table->string('telephone');
$table->string('ifu')->nullable();
$table->text('objet_contrat');
$table->decimal('montant_total',15,2);
$table->text('montant_total_lettre');
$table->decimal('acompte',15,2);
$table->text('acompte_lettre');
$table->decimal('reliquat',15,2);
$table->text('reliquat_lettre');
$table->date('date_limite_livraison');
$table->string('lieu_signature');
$table->date('date_signature');
$table->text('mention_manuelle')->nullable();
$table->enum('etat',
[
'brouillon',
'validé',
'terminé',
'annulé'
])->default('brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_prestataires');
    }
};
