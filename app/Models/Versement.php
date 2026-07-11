<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;

    protected $table = 'versements';

    protected $fillable = [

        'investissement_id',
        'date_versement',
        'montant',
        'mode_paiement',
        'reference',
        'observation',

    ];

    protected $casts = [

        'date_versement' => 'date',
        'montant' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function investissement()
    {
        return $this->belongsTo(Investissement::class);
    }
}