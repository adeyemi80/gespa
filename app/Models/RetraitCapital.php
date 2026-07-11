<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetraitCapital extends Model
{
    use HasFactory;

    protected $table = 'retraits_capital';

    protected $fillable = [

        'investissement_id',

        'date_retrait',

        'montant',

        'mode_retrait',

        'reference',

        'motif',

        'observation',

    ];

    protected $casts = [

        'date_retrait' => 'date',

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