<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdPresence extends Model
{
    use HasFactory;
    protected $fillable = ['td_seance_id', 'eleve_id', 'present'];

    protected $casts = ['present' => 'boolean'];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function seance()
    {
        return $this->belongsTo(TdSeance::class, 'td_seance_id');
    }
}
