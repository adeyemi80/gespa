<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
   protected $fillable = [
        'inscription_id',
        'matiere_id',
        'trimestre_id',
        'annee_id',
        'classe_id',
        'moyenne_interro',
        'devoir1',
        'devoir2',
        'moyenne_matiere',
        'appreciation',
        'interrogation1',
         'interrogation2',
          'interrogation3',
        
    ];

  
public function inscription()
{
    return $this->belongsTo(Inscription::class);
}

// Pour accéder directement à l'élève via l'inscription
public function eleve()
{
    return $this->hasOneThrough(Eleve::class, Inscription::class, 'id', 'id', 'inscription_id', 'eleve_id');
}


    // Note appartient à une matière
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
    public function trimestre()
{
    return $this->belongsTo(Trimestre::class);
}
public function annee()     { return $this->belongsTo(Annee::class); }

public function classe()
{
    return $this->belongsTo(Classe::class);
}


}
