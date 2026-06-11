<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdSession extends Model
{
    use HasFactory;
    protected $fillable = ['date_td', 'annee_id', 'classe_id'];

    public function participations()
{
    return $this->hasMany(TdParticipation::class);
}

public function classe()
{
    return $this->belongsTo(Classe::class);
}

}
