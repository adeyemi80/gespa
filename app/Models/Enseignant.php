<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Enseignant extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom', 'prenom', 'date_naissance', 'sexe',
        'adresse', 'telephone', 'email', 'matricule', 'specialite',
        'grade', 'date_embauche', 'statut', 'matiere_id', 'cycle_id', 'classe_id'
    ];

    public function classes()
{
    return $this->belongsToMany(Classe::class);
}


protected static function boot()
{
    parent::boot();

    static::creating(function ($enseignant) {

        if (empty($enseignant->matricule)) {

            $year = date('Y');
            $random = strtoupper(Str::random(4));

            $enseignant->matricule = 'LGE-' . $year . '-' . $random;
        }
    });
}
public function matiere()
{
    return $this->belongsTo(Matiere::class);
}

}
