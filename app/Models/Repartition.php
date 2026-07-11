<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repartition extends Model
{
    use HasFactory;

    protected $fillable = [

        'benefice_id',
        'investissement_id',
        'pourcentage',
        'montant',
        'statut',
        'observation'

    ];

    protected $casts = [

        'pourcentage' => 'decimal:4',
        'montant' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function benefice()
    {
        return $this->belongsTo(Benefice::class);
    }

    public function investissement()
    {
        return $this->belongsTo(Investissement::class);
    }

    public function paiements()
    {
        return $this->hasMany(PaiementBenefice::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
    |--------------------------------------------------------------------------
    */

    public function getMontantPayeAttribute()
    {
        return $this->paiements()->sum('montant');
    }

    public function getResteAttribute()
    {
        return $this->montant - $this->montant_paye;
    }
}