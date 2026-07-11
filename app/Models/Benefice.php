<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefice extends Model
{
    use HasFactory;

    protected $table = 'benefices';

    protected $fillable = [

        'date_debut',
        'date_fin',
        'montant',
        'observation',

    ];

    protected $casts = [

        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function repartitions()
    {
        return $this->hasMany(Repartition::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
    |--------------------------------------------------------------------------
    */

    public function getPeriodeAttribute()
    {
        return $this->date_debut->format('d/m/Y') .
            ' - ' .
            $this->date_fin->format('d/m/Y');
    }

    public function getMontantFormateAttribute()
    {
        return number_format($this->montant, 2, ',', ' ');
    }
}