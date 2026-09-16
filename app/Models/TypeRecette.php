<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeRecette extends Model
{
    use HasFactory;

    protected $table = 'types_recettes';

    protected $fillable = [
        'categories_recettes_id',
        'nom',
        'description',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];
    
public function categorieRecette()
    {
        return $this->belongsTo(
            CategorieRecette::class,
            'categories_recettes_id'
        );
    }


    public function recettes()
    {
        return $this->hasMany(Recette::class, 'type_recette_id');
    }
}