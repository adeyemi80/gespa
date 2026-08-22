<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementAchat extends Model
{
    protected $table = 'paiement_achats';

    protected $fillable = [
        'numero_recu',
        'inscription_id',
        'nom',
        'prenom',
        'telephone',
        'frais_id',
        'categorie_recette_id',
        'montant',
        'mode_paiement',
        'date_paiement',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    public function inscription()
    {
        return $this->belongsTo(
            Inscription::class
        );
    }

    public function frais()
    {
        return $this->belongsTo(
            Frais::class
        );
    }

    public function categorieRecette()
    {
        return $this->belongsTo(
            CategorieRecette::class,
            'categorie_recette_id'
        );
    }
}