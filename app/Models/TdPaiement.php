<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdPaiement extends Model
{
    protected $fillable = [
        'eleve_id',
        'annee_id',
        'montant',
        'date_paiement',
        'reference',
        'observation',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant'       => 'float',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }
}