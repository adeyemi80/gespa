<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieDepense extends Model
{
    use HasFactory;

    protected $table = 'categories_depenses';

    protected $fillable = [
        'code',
        'nom',
        'description',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function typesDepenses(): HasMany
    {
        return $this->hasMany(TypeDepense::class, 'categorie_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(BudgetDepense::class, 'categorie_id');
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}