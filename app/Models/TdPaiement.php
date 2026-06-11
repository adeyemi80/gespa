<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdPaiement extends Model
{
    use HasFactory;
    protected $fillable = ['td_participation_id', 'montant', 'paye', 'type_frais'];


    public function participation()
    {
        return $this->belongsTo(TdParticipation::class, 'td_participation_id');
    }
}
