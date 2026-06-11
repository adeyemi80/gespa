<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportationNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'classe_id',
        'matiere_id',
        'trimestre_id',
        'annee_id',
        
        
    ];

    

    // Note appartient à une matière
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
    public function trimestre()
{
    return $this->belongsTo(Trimestre::class);
}
public function annee()     { return $this->belongsTo(Annee::class); }

public function classe()
{
    return $this->belongsTo(Classe::class);
}


}
