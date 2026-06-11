<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    use HasFactory;
    protected $fillable = [
        'eleve_id',
        'classe_id',
        'annee_id',
        'trimestre_id',
        'moyenne_generale',
        'moyenne_trimestrielle',
        'moyenne_annuelle',
        'moyenne_scientifique',
        'moyenne_litteraire',
        'rang_trimestre',
        'rang_annuel',
        'appreciation',
    ];

    // Bulletin appartient à un élève
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    // Bulletin appartient à une classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
    public function bulletin()
    {
        return $this->belongsTo(Bulletin::class);
    }
     public function moyenne()
    {
        return $this->belongsTo(Moyenne::class);
    }


    // Bulletin appartient à une année scolaire
    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }

    // Pour récupérer les notes liées à ce bulletin (ex: par élève, trimestre, classe)
    public function notes()
    {
        return $this->hasMany(Note::class, 'eleve_id', 'eleve_id')
                    ->whereHas('matiere', function ($query) {
                        // Optionnel : filtrer les matières de la même classe si besoin
                    });
    }
}
