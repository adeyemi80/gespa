<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;
     protected $fillable = [
        'article_id', 'type', 'quantite', 'prix_unitaire', 'motif', 'date_mouvement'
    ];

    protected $casts = [
        'date_mouvement' => 'datetime', // ← ça permet de faire ->format()
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
