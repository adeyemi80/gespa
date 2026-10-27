<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignement extends Model
{
    protected $table = 'enseignements';

    protected $fillable = [
        'annee_id',
        'classe_id',
        'matiere_id',
        'enseignant_id',
        'coefficient',
        'volume_horaire',
        'heures_par_semaine',
        'actif',
    ];

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
}