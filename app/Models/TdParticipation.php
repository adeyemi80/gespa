<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdParticipation extends Model
{
    use HasFactory;
    protected $fillable = ['td_session_id', 'inscription_id', 'a_participe'];

    public function inscription()
{
    return $this->belongsTo(Inscription::class);
}

public function paiements()
{
    return $this->hasMany(TdPaiement::class, 'td_participation_id');
}

public function td_session()
    {
        return $this->belongsTo(TdSession::class);
    }

    


}
