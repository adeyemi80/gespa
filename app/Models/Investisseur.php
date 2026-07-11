<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investisseur extends Model
{
    use HasFactory;

    protected $fillable = [

        'nom',
        'prenom',
        'telephone',
        'email',
        'adresse',
        'profession',
        'piece_identite',
        'numero_piece',
        'date_naissance',
        'actif',
        'observation',

    ];

    protected $casts = [

        'date_naissance' => 'date',
        'actif' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function investissements()
    {
        return $this->hasMany(Investissement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getNomCompletAttribute()
    {
        return trim($this->nom.' '.$this->prenom);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributs calculés
    |--------------------------------------------------------------------------
    */

    public function getCapitalInvestiAttribute()
    {
        return $this->investissements()->sum('montant');
    }

    public function getCapitalDisponibleAttribute()
    {
        return $this->investissements()
                    ->where('statut', 'actif')
                    ->sum('montant');
    }
}