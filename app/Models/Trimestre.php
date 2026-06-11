<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    use HasFactory;
     protected $fillable = [
        'nom',
        'ordre',
        'periode'

    ];

      public function annees()
    {
        return $this->belongsToMany(Annee::class)
            ->withPivot('active')
            ->withTimestamps();
    }
    public function notes()
{
    return $this->hasMany(Note::class);
}



}
