<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TdTarif extends Model
{
    protected $fillable = ['annee_id', 'categorie', 'type', 'montant'];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    const TYPES = [
        'seance' => 'Par séance',
        'mois'   => 'Par mois',
        'annee'  => 'Par année',
    ];

    const CATEGORIES = [
        'intermediaire' => 'Intermédiaire',
        '3eme'          => '3ème',
        'terminale'     => 'Terminale',
    ];

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    public function getLabelTypeAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getLabelCategorieAttribute(): string
    {
        return self::CATEGORIES[$this->categorie] ?? $this->categorie;
    }
}