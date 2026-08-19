<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratPrestataire extends Model
{
    use HasFactory;

    protected $table = 'contrats_prestataires';

    protected $fillable = [

        // Informations établissement
        'etablissement',
        'adresse_etablissement',
        'representant',
        'fonction',

        // Prestataire
        'prestataire_nom',
        'prestataire_adresse',
        'telephone',
        'ifu',

        // Contrat
        'objet_contrat',

        // Montants
        'montant_total',
        'montant_total_lettre',

        'acompte',
        'acompte_lettre',

        'reliquat',
        'reliquat_lettre',

        // Livraison
        'date_limite_livraison',

        // Signature
        'lieu_signature',
        'date_signature',

        // Décharge
        'mention_manuelle',

        // Etat
        'etat',
    ];

    protected $casts = [

        'montant_total' => 'decimal:2',
        'acompte' => 'decimal:2',
        'reliquat' => 'decimal:2',

        'date_limite_livraison' => 'date',
        'date_signature' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
    |--------------------------------------------------------------------------
    */

    public function getMontantTotalFormateAttribute()
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' F CFA';
    }

    public function getAcompteFormateAttribute()
    {
        return number_format($this->acompte, 0, ',', ' ') . ' F CFA';
    }

    public function getReliquatFormateAttribute()
    {
        return number_format($this->reliquat, 0, ',', ' ') . ' F CFA';
    }

    /*
    |--------------------------------------------------------------------------
    | Mutateurs
    |--------------------------------------------------------------------------
    */

    public function setTelephoneAttribute($value)
    {
        $this->attributes['telephone'] = trim($value);
    }

    public function setPrestataireNomAttribute($value)
    {
        $this->attributes['prestataire_nom'] = strtoupper($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeBrouillon($query)
    {
        return $query->where('etat', 'brouillon');
    }

    public function scopeValide($query)
    {
        return $query->where('etat', 'validé');
    }

    public function scopeTermine($query)
    {
        return $query->where('etat', 'terminé');
    }

    public function scopeAnnule($query)
    {
        return $query->where('etat', 'annulé');
    }

    /*
    |--------------------------------------------------------------------------
    | Méthodes
    |--------------------------------------------------------------------------
    */

    /**
     * Recalcule automatiquement le reliquat.
     */
    public function calculerReliquat()
    {
        $this->reliquat = $this->montant_total - $this->acompte;
    }

    /**
     * Vérifie si le contrat est terminé.
     */
    public function estTermine()
    {
        return $this->etat === 'terminé';
    }

    /**
     * Vérifie si le contrat est validé.
     */
    public function estValide()
    {
        return $this->etat === 'validé';
    }

    /**
     * Vérifie si le contrat est annulé.
     */
    public function estAnnule()
    {
        return $this->etat === 'annulé';
    }
}