<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inscription extends Model
{
    use HasFactory;

     protected $fillable = [
        
        'annee_id',
        'classe_id',
         'eleve_id',
          'note_id',
         'date_inscription',
         'decision',
           'passage_auto',
           'ancienne_classe_id',
         'moyenne_annuelle'
    ];

    /**
     * Relation avec le trimestre (si un inscription appartient à un trimestre)
     */
    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class);
    }

    /**
     * Ou si plusieurs trimestres sont possibles (relation inverse)
     */
    public function trimestres(): HasMany
    {
        return $this->hasMany(Trimestre::class, 'inscription_id');
    }

    /**
     * Relation avec l'élève
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    /**
     * Relation avec l'année scolaire
     */
    public function annee(): BelongsTo
    {
        return $this->belongsTo(Annee::class);
    }

    public function classe(): BelongsTo
    { 
    return $this->belongsTo(Classe::class, 'classe_id'); 
    }

    public function paiements(): HasMany 
    { 
    return $this->hasMany(Paiement::class); 
    }

    public function frais()
{
    return $this->belongsToMany(Frais::class, 'inscription_frais')
        ->withPivot([
            'annee_id',
            'montant_frais',
            'montant_paye',
            'reste',
            'statut',
            'est_arriere'
        ])
        ->withTimestamps();
}


    public function scopeAlphabetique($query)
{
    return $query
        ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
        ->orderBy('eleves.nom', 'asc')
        ->orderBy('eleves.prenom', 'asc')
        ->select('inscriptions.*');
}

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'inscription_id');
    }
    public function conduites()
{
    return $this->hasMany(\App\Models\Conduite::class, 'inscription_id');
}
// Dans App\Models\Inscription
public function moyennes()
{
    return $this->hasMany(\App\Models\Moyenne::class, 'inscription_id', 'id');
}

    public function paren()
{
    return $this->hasOneThrough(
        Paren::class, // le modèle final
        Eleve::class, // le modèle intermédiaire
        'id',         // clé primaire de Eleve (foreignKey sur Eleve ?)
        'id',         // clé primaire de Paren (foreignKey sur Paren ?)
        'eleve_id',   // clé étrangère sur Inscription vers Eleve
        'paren_id'    // clé étrangère sur Eleve vers Paren
    );
}
public function tdParticipations()
{
    return $this->hasMany(TdParticipation::class);
}

public function inscriptionFrais()
    {
        return $this->hasMany(InscriptionFrais::class);
    }
    // app/Models/Inscription.php

protected static function boot()
{
    parent::boot();

    static::saving(function ($inscription) {
        if ($inscription->moyenne_annuelle !== null) {
            // ✅ Valeurs exactes acceptées par l'enum PostgreSQL
            $inscription->decision = $inscription->moyenne_annuelle >= 10
                ? 'passé'      // ← avec accent, minuscule
                : 'redoublé';  // ← avec accent, minuscule
        }
    });
}

public function moyenne()
{
    return $this->hasOne(Moyenne::class, 'inscription_id');
}

    
}