<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeClasseFrais extends Model
{
    use HasFactory;
    protected $table = 'annee_classe_frais';

    protected $fillable = [
        'annee_id',
        'classe_id',
        'frais_id',
        'montant',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function frais()
    {
        return $this->belongsTo(Frais::class);
    }
}