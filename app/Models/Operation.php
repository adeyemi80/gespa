<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'libelle',
        'montant',
        'description',
        'categorie',
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2',
    ];
}
