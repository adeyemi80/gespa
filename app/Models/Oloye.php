<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oloye extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'libelle',
        'categorie',
        'montant',
        'beneficiaire',
        'observation',
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2',
    ];
}