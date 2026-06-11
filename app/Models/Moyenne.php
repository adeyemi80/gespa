<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GereMentions;

class Moyenne extends Model
{
    use HasFactory, GereMentions;

    protected $fillable = [
        'trimestre_id',
        'annee_id',
        'moyenne_trimestrielle',
        'moyenne_annuelle',
        'classe_id',
        'rang_trimestre',
        'rang_annuel',
        'moyenne_scientifique',
        'moyenne_litteraire',
        'inscription_id',
        'notes',
        'note_conduite',
        'appreciation_conduite',
        'mention',
        'appreciation',
        'total_eleves',
        'plus_faible_moyenne',
        'plus_forte_moyenne',
        'moyenne_t1',
        'moyenne_t2',
        'moyenne_t3',
        'decision'
    ];

    protected $casts = [
        'moyenne_trimestrielle' => 'float',
        'moyenne_annuelle' => 'float',
        'moyenne_scientifique' => 'float',
        'moyenne_litteraire' => 'float',
        'notes' => 'array',
    ];

    // ✅ Accesseur pour les notes (tableau JSON)
    public function getNotesAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    // ✅ Accesseurs pour les champs de conduite
    public function getNoteConduiteAttribute()
    {
        return $this->attributes['note_conduite'] ?? null;
    }

    public function getAppreciationConduiteAttribute()
    {
        return $this->attributes['appreciation_conduite'] ?? null;
    }

    // ✅ Accesseur automatique pour la mention
    public function getMentionAttribute()
    {
        return $this->getMention(
            $this->moyenne_trimestrielle,
            $this->moyenne_annuelle,
            $this->trimestre_id
        );
    }

    // ✅ Accesseurs pour les moyennes
    public function getMoyenneTrimestreAttribute()
    {
        return $this->attributes['moyenne_trimestrielle'] ?? null;
    }

    public function getMoyenneAnnuelleAttribute()
    {
        return $this->attributes['moyenne_annuelle'] ?? null;
    }

    public function getMoyenneScientifiqueAttribute()
    {
        return $this->attributes['moyenne_scientifique'] ?? null;
    }

    public function getMoyenneLitteraireAttribute()
    {
        return $this->attributes['moyenne_litteraire'] ?? null;
    }

    // ✅ Accesseurs pour les rangs
    public function getRangTrimestreAttribute()
    {
        return $this->attributes['rang_trimestre'] ?? null;
    }

    public function getRangAnnuelAttribute()
    {
        return $this->attributes['rang_annuel'] ?? null;
    }

    // ✅ Accesseur pour l'appréciation générale
    public function getAppreciationAttribute()
    {
        return $this->attributes['appreciation'] ?? null;
    }

    // ✅ Accesseurs pour les autres champs
    public function getTotalElevesAttribute()
    {
        return $this->attributes['total_eleves'] ?? null;
    }

    public function getPlusFaibleMoyenneAttribute()
    {
        return $this->attributes['plus_faible_moyenne'] ?? null;
    }

    public function getPlusForteMoyenneAttribute()
    {
        return $this->attributes['plus_forte_moyenne'] ?? null;
    }

    public function getMoyenneT1Attribute()
    {
        return $this->attributes['moyenne_t1'] ?? null;
    }

    public function getMoyenneT2Attribute()
    {
        return $this->attributes['moyenne_t2'] ?? null;
    }

    public function getMoyenneT3Attribute()
    {
        return $this->attributes['moyenne_t3'] ?? null;
    }

    public function getDecisionAttribute()
    {
        return $this->attributes['decision'] ?? null;
    }

    // ✅ Relations
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }
}