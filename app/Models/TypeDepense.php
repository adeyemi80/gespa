<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeDepense extends Model
{
    use HasFactory;

    protected $table = 'types_depenses';

    protected $fillable = [
        'categorie_id',
        //'code',
        'nom',
        'description',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieDepense::class, 'categorie_id');
    }

    public function depenses(): HasMany
    {
        return $this->hasMany(Depense::class, 'type_depense_id');
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}