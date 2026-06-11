<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionFrais extends Model
{
    use HasFactory;

    protected $table = 'inscription_frais';

    // Les colonnes autorisées à la création / mise à jour
    protected $fillable = [
        'inscription_id',
        'frais_id',
        'annee_id',
        'montant_frais',
        'montant_paye',
        'montant_total',
        'reste',
        'statut',
        'est_arriere',
    ];

    /**
     * Relation vers l'inscription
     */
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    /**
     * Relation vers le frais
     */
    public function frais()
    {
        return $this->belongsTo(Frais::class);
    }

    /**
     * Relation vers l'année scolaire
     */
    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    /**
     * Vérifie si le frais est soldé
     */
    public function isSolde(): bool
    {
        return $this->reste <= 0;
    }
}
