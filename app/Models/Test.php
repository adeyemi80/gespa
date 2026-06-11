<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = [
        'annee_id',
        'trimestre_id',
        'date',
        'titre',
        'type',
        'matiere_id',
        'classe_id',
        'fichier',
        'hash',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // relations (si tu as ces modèles)
    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }
}
