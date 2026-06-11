<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

     protected $fillable = [
        'nom',
        'solde_initial',
        'solde_actuel',
    ];

    /**
     * Un compte possède plusieurs transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Met à jour automatiquement le solde en fonction des transactions.
     */
    public function majSolde()
    {
        $recettes = $this->transactions()->where('type', 'recette')->sum('montant');
        $depenses = $this->transactions()->where('type', 'depense')->sum('montant');
        $this->solde_actuel = $this->solde_initial + $recettes - $depenses;
        $this->save();
    }
}
