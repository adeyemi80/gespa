<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieRecette extends Model
{
    use HasFactory;

    protected $table = 'categories_recettes';

    protected $fillable = [
        'code',
        'nom',
        'description',
        'actif',
        'est_achat',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function budgets(): HasMany
    {
        return $this->hasMany(BudgetRecette::class, 'categorie_id');
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
    public function frais()
{
    return $this->hasMany(
        Frais::class,
        'categorie_recette_id'
    );
}
}