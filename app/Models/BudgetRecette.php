<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetRecette extends Model
{
    use HasFactory;

    protected $table = 'budgets_recettes';

    protected $fillable = [
        'categorie_id',
        'annee_id',
        'montant_prevu',
        'montant_realise',
    ];

    protected $casts = [
        'montant_prevu' => 'decimal:2',
        'montant_realise' => 'decimal:2',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieRecette::class, 'categorie_id');
    }

    public function annee(): BelongsTo
    {
        return $this->belongsTo(Annee::class, 'annee_id');
    }

    public function getMontantEcartAttribute(): float
    {
        return (float) $this->montant_realise - (float) $this->montant_prevu;
    }

    public function getTauxRealisationAttribute(): float
    {
        if ((float) $this->montant_prevu <= 0) {
            return 0;
        }

        return round(((float) $this->montant_realise / (float) $this->montant_prevu) * 100, 2);
    }
}