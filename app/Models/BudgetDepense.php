<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetDepense extends Model
{
    use HasFactory;

    protected $table = 'budgets_depenses';
    protected $fillable = [
        'categorie_id',
        'annee_id',
        'montant_alloue',
        'montant_utilise',
    ];

    protected $casts = [
        'montant_alloue' => 'decimal:2',
        'montant_utilise' => 'decimal:2',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieDepense::class, 'categorie_id');
    }

    public function annee(): BelongsTo
    {
        return $this->belongsTo(Annee::class, 'annee_id');
    }

    public function getMontantRestantAttribute(): float
    {
        return (float) $this->montant_alloue - (float) $this->montant_utilise;
    }

    public function getTauxUtilisationAttribute(): float
    {
        if ((float) $this->montant_alloue <= 0) {
            return 0;
        }

        return round(((float) $this->montant_utilise / (float) $this->montant_alloue) * 100, 2);
    }
}