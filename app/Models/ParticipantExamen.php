<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantExamen extends Model
{
    use HasFactory;

    protected $fillable = ['examen_blanc_id', 'inscription_id', 'numero_table'];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function notes()
    {
        return $this->hasMany(NoteExamen::class, 'participant_id');
    }
    // ParticipantExamen.php
public function examen()
{
    return $this->belongsTo(ExamenBlanc::class, 'examen_blanc_id');
}




}
