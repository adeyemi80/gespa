<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeTrimestre extends Model
{
    use HasFactory;

    protected $table = 'annee_trimestre'; // nom de la table pivot

    protected $fillable = [
        'annee_id',
        'trimestre_id',
    ];

    /**
     * Relation vers l'année
     */
    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    /**
     * Relation vers le trimestre
     */
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }
}
