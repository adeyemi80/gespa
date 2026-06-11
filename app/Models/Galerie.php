<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galerie extends Model
{
    use HasFactory;
    protected $fillable = ['titre', 'description'];

    public function medias()
    {
        return $this->hasMany(Media::class);
    }
}
