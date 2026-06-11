<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'annee_id',
        'categorie_id',
        'montant_prevu',
        'periode',
        'nom'
    ];

    /**
     * Un budget est lié à une catégorie.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Un budget peut être lié à une année scolaire.
     */
    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }
}
