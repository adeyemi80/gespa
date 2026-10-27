<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaiementAchat extends Model
{
    protected $table = 'paiement_achats';

    protected $fillable = [
        'numero_recu',
        'inscription_id',
        'inscription_td_externe_id',
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

    /**
     * Inscription scolaire normale.
     */
    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    /**
     * Inscription aux TD d'un élève externe.
     */
    public function inscriptionTdExterne(): BelongsTo
    {
        return $this->belongsTo(
            InscriptionTdExterne::class,
            'inscription_td_externe_id'
        );
    }

    /**
     * Recette associée au paiement.
     */
    public function recette(): HasOne
    {
        return $this->hasOne(
            Recette::class,
            'paiement_achat_id'
        );
    }

    /**
     * Frais payés.
     */
    public function frais(): BelongsTo
    {
        return $this->belongsTo(Frais::class);
    }

    /**
     * Catégorie de recette.
     */
    public function categorieRecette(): BelongsTo
    {
        return $this->belongsTo(
            CategorieRecette::class,
            'categorie_recette_id'
        );
    }
}