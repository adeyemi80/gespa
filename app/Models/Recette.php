<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recette extends Model
{
    use HasFactory;

    // Champs qu'on peut remplir en masse
    protected $fillable = [
        'paiement_id',
        'inscription_id',
        'montant_verse',
        'date_paiement',
        'mode_paiement',
        'numero_recu'
    ];

    // Relation vers le paiement
    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
