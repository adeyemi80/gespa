<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EleveExterneTd extends Model
{
    use HasFactory;

    protected $table = 'eleves_externes_td';

    protected $fillable = [
    'matricule',
    'nom',
    'prenom',
    'sexe',
    'date_naissance',
    'telephone',
    'telephone_parent',
    'adresse',
    'observations',
    'actif',
   
];

    protected $casts = [
        'date_naissance' => 'date',
        'actif' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Toutes les inscriptions aux TD de cet élève.
     */
    public function inscriptionsTd(): HasMany
    {
        return $this->hasMany(
            InscriptionTdExterne::class,
            'eleve_externe_td_id'
        );
    }

    /**
     * Inscriptions aux TD pour une année scolaire donnée.
     */
    public function inscriptionsTdPourAnnee(int $anneeId): HasMany
    {
        return $this->inscriptionsTd()
            ->where('annee_id', $anneeId);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
    |--------------------------------------------------------------------------
    */

    /**
     * Nom complet de l'élève.
     */
    public function getNomCompletAttribute(): string
    {
        return trim($this->nom . ' ' . $this->prenom);
    }

    /**
     * Nombre d'inscriptions aux TD.
     */
    public function getNombreInscriptionsTdAttribute(): int
    {
        return $this->inscriptionsTd()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Élèves externes actifs.
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Recherche par nom, prénom ou matricule.
     */
    public function scopeRechercher($query, ?string $terme)
    {
        if (blank($terme)) {
            return $query;
        }

        $terme = trim($terme);

        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'ilike', "%{$terme}%")
                ->orWhere('prenom', 'ilike', "%{$terme}%")
                ->orWhere('matricule', 'ilike', "%{$terme}%");
        });
    }

     public function inscriptions(): HasMany
    {
        return $this->hasMany(
            InscriptionTdExterne::class,
            'eleve_externe_td_id'
        );
    }
}