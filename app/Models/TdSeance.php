<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdSeance extends Model
{
    use HasFactory;
     protected $fillable = ['annee_id','classe_id', 'date', 'libelle'];

    protected $casts = ['date' => 'date'];

    public function presences()
    {
        return $this->hasMany(TdPresence::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }
}
