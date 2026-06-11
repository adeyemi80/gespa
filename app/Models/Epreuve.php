<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epreuve extends Model
{
    use HasFactory;

    protected $fillable = [
        'examen_blanc_id',
        'matiere_id',
        'date',
        'heure_debut',
        'heure_fin'
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
