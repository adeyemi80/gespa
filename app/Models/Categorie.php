<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

     protected $fillable = [
        'nom',
        'type',
        'description',
    ];

    /**
     * Une catégorie peut avoir plusieurs transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Une catégorie peut être liée à plusieurs budgets.
     */
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
