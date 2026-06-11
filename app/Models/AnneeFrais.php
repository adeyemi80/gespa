<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeFrais extends Model
{
    use HasFactory;
    protected $table = 'annee_frais';

    protected $fillable = [
        'annee_id',
        'frais_id',
        'montant',
        'description',
    ];

    // Relation vers l'année
    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    // Relation vers le frais
    public function frais()
    {
        return $this->belongsTo(Frais::class);
    }
}
