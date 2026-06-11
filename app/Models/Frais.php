<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frais extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'description',
        'montant',
    ];

    // 🔗 Plusieurs classes
   public function classes()
{
    return $this->belongsToMany(
        Classe::class,
        'annee_classe_frais',
        'frais_id',
        'classe_id'
    )->withPivot(['annee_id', 'montant']);
}

    // 🔗 Plusieurs années
    public function annees()
    {
        return $this->belongsToMany(
            Annee::class,
            'annee_frais',
            'frais_id',
            'annee_id'
        );
    }

    public function paiements()
{
    return $this->hasMany(Paiement::class);
}
public function inscriptions()
{
    return $this->belongsToMany(Inscription::class, 'inscription_frais')
        ->withPivot([
            'annee_id',
            'montant_frais',
            'montant_paye',
            'reste',
            'statut',
            'est_arriere'
        ]);
}


    public function echeances()
    {
        return $this->hasMany(Echeance::class);
    }


    public function resteAPayer($eleve_id)
    {
        $totalPaye = Paiement::where('eleve_id', $eleve_id)
                             ->where('frais_id', $this->id)
                             ->sum('montant_verse');

        return $this->montant_total - $totalPaye;
    }

public function anneeClasseFrais()
{
    return $this->hasMany(AnneeClasseFrais::class);
}

}
