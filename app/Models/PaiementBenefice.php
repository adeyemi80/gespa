<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementBenefice extends Model
{
    use HasFactory;

    protected $table = 'paiements_benefices';

    protected $fillable = [

        'repartition_id',
        'date_paiement',
        'montant',
        'mode_paiement',
        'reference',
        'observation'

    ];

    protected $casts = [

        'date_paiement' => 'date',
        'montant' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function repartition()
    {
        return $this->belongsTo(Repartition::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
    |--------------------------------------------------------------------------
    */

    public function getInvestisseurAttribute()
    {
        return $this->repartition?->investissement?->investisseur;
    }

    public function getInvestissementAttribute()
    {
        return $this->repartition?->investissement;
    }

    public function getBeneficeAttribute()
    {
        return $this->repartition?->benefice;
    }
}