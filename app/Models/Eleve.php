<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;
      protected $table = 'eleves';

    protected $fillable = [
        'matricule',
        'numeducmaster',
        'nom',
        'prenom',
        'statut',
        'date_naissance',
        'sexe',
        'nationalite',
        'lieu_naissance',
        'classe_id',
        'annee_id',
        'paren_id',
         'photo',
       // 'nom_mere',
      // 'prenom_mere', // si applicable
        //'email',
        //'adresse'
    ];


// app/Models/Eleve.php
public function annee()
{
    return $this->belongsTo(Annee::class);
}
     // app/Models/Eleve.php
public function classe()
{
    return $this->belongsTo(Classe::class);
}

    public function paren()
    {
        return $this->belongsTo(Paren::class, 'paren_id');
    }

   public function inscriptions()
     {
    return $this->hasMany(\App\Models\Inscription::class, 'eleve_id');
     }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function scolarites()
    {
        return $this->hasMany(Scolarite::class);
    }

    public function bulletins()
    {
        return $this->hasMany(Bulletin::class);
    }
    public function paiements() {
    return $this->hasMany(Paiement::class);
    }

    public function conduites()
    {
        return $this->hasMany(Conduite::class);
    }

    public function moyennes()
   {
    return $this->hasMany(Moyenne::class);
    }
    public function messagesParents()
{
    return $this->hasMany(MessageParent::class, 'eleve_id');
}

public function comportements()
{
    return $this->hasMany(Comportement::class);
}

public function resteParFrais(Frais $frais)
{
    $totalPaye = $this->paiements()
                      ->where('frais_id', $frais->id)
                      ->sum('montant_paye');

    return $frais->montant_total - $totalPaye;
}

public function resteParEcheance(Echeance $echeance)
{
    $totalPaye = $this->paiements()
                      ->where('echeance_id', $echeance->id)
                      ->sum('montant_paye');

    return $echeance->montant - $totalPaye;
}
public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Méthode pour vérifier si l'élève est inscrit dans une année donnée
     */
    public static function estInscrit($matricule, $anneeId)
    {
        return self::where('matricule', $matricule)
                    ->where('annee_id', $anneeId)
                    ->exists();
    }
public function scopeAlphabetique($query)
{
    return $query->orderBy('nom')->orderBy('prenom'); ///$eleves = Eleve::alphabetique()->get();
}

}
