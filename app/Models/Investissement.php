<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investissement extends Model
{
    use HasFactory;

    const ACTIF = 'actif';
    const RETIRE = 'retire';
    const SUSPENDU = 'suspendu';
    const TERMINE = 'termine';

    protected $fillable = [

        'investisseur_id',
        'reference',
        'date_investissement',
        'montant',
        'taux',
        'statut',
        'observation',

    ];

    protected $casts = [

        'date_investissement' => 'date',
        'montant' => 'decimal:2',
        'taux' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function investisseur()
    {
        return $this->belongsTo(Investisseur::class);
    }

    public function versements()
    {
        return $this->hasMany(Versement::class);
    }

    public function repartitions()
    {
        return $this->hasMany(Repartition::class);
    }

    public function retraitsCapital()
    {
        return $this->hasMany(RetraitCapital::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActifs($query)
    {
        return $query->where('statut', self::ACTIF);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributs calculés
    |--------------------------------------------------------------------------
    */

    public function getTotalVersementsAttribute()
    {
        return $this->versements()->sum('montant');
    }

    public function getTotalBeneficesAttribute()
    {
        return $this->repartitions()->sum('montant');
    }

    public function getCapitalRetireAttribute()
    {
        return $this->retraitsCapital()->sum('montant');
    }

    public function getCapitalRestantAttribute()
    {
        return $this->montant - $this->capital_retire;
    }
}