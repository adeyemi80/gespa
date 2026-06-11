<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

     protected $fillable = [
        
        'inscription_id',
        'frais_id',
         'date_paiement',
         'montant_verse',
         'montant_total',
         'mode_paiement',
         'numero_recu',
         'reference'
    ];
protected $casts = [
    'date_paiement' => 'date',
];

    public function inscription() {
    return $this->belongsTo(Inscription::class);
    }

    public function frais() {
    return $this->belongsTo(Frais::class);
    }
      // Relation avec la recette
    public function recette()
    {
        return $this->hasOne(Recette::class);
    }

    // Créer automatiquement une recette après paiement
  
// app/Models/Paiement.php
public function inscriptionFrais()
{
    return $this->hasOne(InscriptionFrais::class, 'inscription_id', 'inscription_id')
                ->where('frais_id', $this->frais_id)
                ->where('annee_id', $this->inscription->annee_id);
}
public function details()
{
    return $this->hasMany(PaiementDetail::class);
}


}
