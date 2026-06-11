<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'niveau',
         'type',
        'coefficient',
        'enseignant_id',
    ];

    // Matiere appartient à une classe
public function classes()
{
    return $this->belongsToMany(Classe::class, 'classe_matiere', 'matiere_id', 'classe_id');
}


    // Matiere appartient à un enseignant (optionnel)
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    // Matiere a plusieurs notes
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
