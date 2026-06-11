<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenBlanc extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'annee_id', 'classe_id', 'date_debut', 'date_fin'];

 public function classes()
{
    return $this->belongsToMany(
        Classe::class,
        'examen_classes',      // 🔥 nom exact de ta table
        'examen_blanc_id',    // 🔥 clé étrangère correcte
        'classe_id'           // 🔥 clé étrangère correcte
    );
}
    public function participants()
    {
        return $this->hasMany(ParticipantExamen::class);
    }

    public function epreuves()
    {
        return $this->hasMany(Epreuve::class);
    }
     public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}
