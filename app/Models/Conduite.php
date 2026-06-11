<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conduite extends Model
{
    use HasFactory;

    protected $fillable = [
         'matricule',
         'annee_id',
        'classe_id',
        'inscription_id',
        'trimestre_id',
        'note_conduite',
    ];

    /**
     * Relation vers la classe concernée.
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Relation vers l'élève concerné.
     */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
    public function inscription()
{
    return $this->belongsTo(Inscription::class);
}

    /**
     * Relation vers le trimestre concerné.
     */
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }

    public function annee()
    {
        return $this->belongsTo(Annee::class, 'annee_id');
    }
}
