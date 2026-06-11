<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'ordre',
    ];

    /**
     * Un cycle possède plusieurs classes
     */
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
