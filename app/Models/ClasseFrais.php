<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasseFrais extends Model
{
    use HasFactory;
     protected $table = 'classe_frais';

    protected $fillable = ['classe_id', 'frais_id'];
}
