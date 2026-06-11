<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
     protected $fillable = [
        'type_id',
        'nom',
        'reference',
        'prix_achat',
        'prix_vente',
        'stock_min',
        'description'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }

    // 📊 Stock réel
    public function stockActuel()
    {
        $entrees = $this->mouvements()->where('type','entree')->sum('quantite');
        $sorties = $this->mouvements()->where('type','sortie')->sum('quantite');

        return $entrees - $sorties;
    }

    // ⚠️ Alerte stock bas
    public function stockCritique()
    {
        return $this->stockActuel() <= $this->stock_min;
    }
}
