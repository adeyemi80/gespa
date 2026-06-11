<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_transaction',
        'type',
        'categorie_id',
        'compte_id',
        'montant',
        'mode_paiement',
        'description',
        'created_by',
    ];

    /**
     * Une transaction appartient à une catégorie.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Une transaction appartient à un compte.
     */
    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    /**
     * Utilisateur qui a créé la transaction.
     */
    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
