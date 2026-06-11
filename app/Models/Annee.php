<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Annee extends Model
{
    use HasFactory;

   public function classes()
{
    return $this->belongsToMany(
        Classe::class,   // modèle lié
        'annee_classe',  // table pivot
        'annee_id',      // clé étrangère vers Annee
        'classe_id'      // clé étrangère vers Classe
    )
    ->withPivot('active')       // inclut la colonne 'active' du pivot
    ->wherePivot('active', true) // filtre seulement les lignes actives
    ->withTimestamps();        // gère created_at / updated_at sur pivot
}
    
   public function classesActives()
{
    return $this->belongsToMany(Classe::class, 'annee_classe')
        ->wherePivot('active', true);
}

    public function inscriptions(): HasMany 
    { 
    return $this->hasMany(Inscription::class); 
    }

    protected $fillable = [
        'nom',
         'debut',
          'fin',
           'en_cours',
    ];

    public function attacherToutesLesClasses()
{
    $classes = Classe::all();
    foreach ($classes as $classe) {
        ClasseAnnee::firstOrCreate([
            'classe_id' => $classe->id,
            'annee_id' => $this->id
        ]);
    }
}

/**public function trimestres()
{
    return $this->belongsToMany(Trimestre::class, 'annee_trimestre')
                ->withPivot('active')
                ->withTimestamps();
}*/
public function trimestres()
{
    return $this->belongsToMany(Trimestre::class, 'annee_trimestre', 'annee_id', 'trimestre_id')
                ->select('trimestres.id as id', 'trimestres.nom'); // on précise la table
}
public function trimestresActifs()
{
     return $this->trimestres()->wherePivot('active', true);
}
 public function frais()
    {
        return $this->belongsToMany(
            Frais::class,
            'annee_frais',
            'annee_id',
            'frais_id'
        );
    }
public function anneeClasseFrais()
{
    return $this->hasMany(AnneeClasseFrais::class);
}



}
