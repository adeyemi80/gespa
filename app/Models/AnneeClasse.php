<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeClasse extends Model
{
    protected $table = 'annee_classe';

    protected $fillable = [
        'classe_id',
        'annee_id',
        'active',
    ];

    // Relation vers la classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    // Relation vers l'année
    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }
}
