<?php
// app/Models/Recette.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recette extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'date_paiement',
        'montant_verse',
        'paiement_id',
        'inscription_id',
        'mode_paiement',
        'numero_recu',
        'categorie_recette_id',
        'annee_id',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant_verse' => 'decimal:2',
    ];

    public function paiement(): BelongsTo
    {
        return $this->belongsTo(Paiement::class);
    }

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function categorieRecette(): BelongsTo
    {
        return $this->belongsTo(CategorieRecette::class);
    }

    public function annee(): BelongsTo
    {
        return $this->belongsTo(Annee::class);
    }
}