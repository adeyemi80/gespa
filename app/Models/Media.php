<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
     protected $fillable = [
        'galerie_id',
        'titre',
        'fichier',
        'type',
    ];

    public function galerie()
    {
        return $this->belongsTo(Galerie::class);
    }
}
