<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PieceJustificative extends Model
{
    use HasFactory;

    protected $table = 'pieces_justificatives';
    protected $fillable = [
        'depense_id',
        'nom_fichier',
        'chemin_fichier',
        'type_mime',
        'taille',
    ];

    public function depense(): BelongsTo
    {
        return $this->belongsTo(Depense::class, 'depense_id');
    }
}