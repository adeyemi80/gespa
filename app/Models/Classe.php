<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'niveau',
         'cycle_id',
         'ordre',
         'rang'

    ];

    public function annees()
{
    return $this->belongsToMany(Annee::class, 'annee_classe', 'classe_id', 'annee_id')
                ->withPivot('active')
                ->withTimestamps();
}

public function examensBlancs()
{
    return $this->belongsToMany(ExamenBlanc::class, 'examen_blanc_classe');
}

public function anneesActives()
{
    return $this->annees()->wherePivot('active', true);
}
public function cycle()
{
    return $this->belongsTo(Cycle::class);
}
    
    public function eleves()
     {
    return $this->hasMany(Eleve::class); // ou via inscription
     }

    public function inscriptions(): HasMany 
    { 
    return $this->hasMany(Inscription::class, 'classe_id'); 
    }
 
public function matieres()
{
    return $this->belongsToMany(
        Matiere::class,
        'classe_matiere',
        'classe_id',
        'matiere_id'
    );
}
    public function sujets(): HasMany 
    { 
    return $this->hasMany(Sujet::class, 'classe_id'); 
    }

public function prochaineClasse()
    {
        return Classe::where('niveau', $this->niveau + 1)->first();
    }

    public function frais()
{
    return $this->belongsToMany(
        Frais::class,
        'annee_classe_frais',
        'classe_id',
        'frais_id'
    )->withPivot(['annee_id', 'montant']);
}


public function attacherToutesLesMatieres()
{
    $matieres = Matiere::all();
    foreach ($matieres as $matiere) {
        MatiereClasse::firstOrCreate([
            'classe_id' => $this->id,
            'matiere_id' => $matiere->id
        ]);
    }
}

 public function classesSuperieures()
    {
        return $this->belongsToMany(
            Classe::class,            // modèle cible
            'classe_transitions',     // nom de la table pivot
            'classe_id',              // clé étrangère dans pivot pour cette classe
            'classe_superieure_id'    // clé étrangère dans pivot pour la classe supérieure
        );
    }

public function classeSuperieure()
{
    if (is_null($this->ordre)) {
        return null;
    }

    return self::where('cycle_id', $this->cycle_id)
        ->where('ordre', $this->ordre + 1)
        ->first();
}

    public function classesInferieures()
    {
        return $this->belongsToMany(
            Classe::class,
            'classe_transitions',
            'classe_superieure_id',
            'classe_id'
        );
    }

   public function scopeOrderByNiveau($query)
{
    return $query->orderBy('ordre');
}
public function enseignants()
{
    return $this->belongsToMany(Enseignant::class);
}

public function anneeClasseFrais()
{
    return $this->hasMany(AnneeClasseFrais::class);
}



}
