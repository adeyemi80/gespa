<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementDetail extends Model
{
    use HasFactory;

     protected $fillable = [
        'paiement_id',
        'inscription_frais_id',
        'montant',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }

    public function inscriptionFrais()
    {
        return $this->belongsTo(InscriptionFrais::class);
    }
}
