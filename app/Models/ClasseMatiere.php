<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClasseMatiere extends Model
{
    // Table pivot
    protected $table = 'classe_matiere';

    // Colonnes assignables
    protected $fillable = [
        'matiere_id',
        'classe_id',
    ];

    // Relation vers Matiere
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    // Relation vers Classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}
