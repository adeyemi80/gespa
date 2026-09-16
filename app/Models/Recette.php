<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recette extends Model
{
    protected $table = 'recettes';

    protected $fillable = [
        'date_paiement',
        'montant_verse',
        'paiement_id',
        'inscription_id',
        'mode_paiement',
        'numero_recu',
        'categorie_recette_id',
        'annee_id',
        'paiement_achat_id',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant_verse' => 'decimal:2',
    ];

    public function paiement(): BelongsTo
    {
        return $this->belongsTo(Paiement::class, 'paiement_id');
    }

    public function paiementAchat(): BelongsTo
    {
        return $this->belongsTo(
            PaiementAchat::class,
            'paiement_achat_id'
        );
    }

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(
            Inscription::class,
            'inscription_id'
        );
    }

    public function categorieRecette(): BelongsTo
    {
        return $this->belongsTo(
            CategorieRecette::class,
            'categorie_recette_id'
        );
    }

    public function annee(): BelongsTo
    {
        return $this->belongsTo(
            Annee::class,
            'annee_id'
        );
    }
}
