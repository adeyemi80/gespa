<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echeance extends Model
{
     use HasFactory;

    protected $fillable = ['frais_id', 'classe_id', 'annee_id', 'nom', 'montant', 'date_limite'];

    public function frais()
    {
        return $this->belongsTo(Frais::class);
    }
}
