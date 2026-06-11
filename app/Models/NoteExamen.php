<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteExamen extends Model
{
    use HasFactory;

    // Colonnes autorisées pour le mass assignment
    protected $fillable = [
        'participant_id',
        'matiere_id',
        'note',
        // ajoute d'autres colonnes si nécessaire
    ];

    // Relations
    public function participant()
    {
        return $this->belongsTo(ParticipantExamen::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}