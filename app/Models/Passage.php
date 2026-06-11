<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passage extends Model
{
    use HasFactory;
    protected $fillable = [
        'eleve_id',
        'classe_id',
        'annee_id',
        'trimestre_id',
        'decision',   // par ex. "Admis" / "Redouble"
    ];


    // Relations
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }

}
