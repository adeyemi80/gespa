<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Paren extends Model
{
    use HasFactory;

    protected $table = 'parens'; // car "parents" est un mot réservé en PHP

    protected $fillable = [
        'nom_parent',
        'prenom_parent',
        'telephone_parent',
        'adresse_parent',
        'user_id'
    ];

    /**
     * Un parent peut avoir plusieurs élèves.
     */
    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'paren_id');
    }
    public function messages()
{
    return $this->hasMany(MessageParent::class, 'paren_id');
}

public function notifications()
{
    return $this->hasMany(NotificationParent::class, 'paren_id');
}

public function setPasswordAttribute($value)
{
    // Ne hache que si nouveau mot de passe fourni
    $this->attributes['password'] = $value ? Hash::make($value) : $this->getOriginal('password');
}

public function user()
{
    return $this->belongsTo(User::class);
}
public function inscriptions()
    {
        return $this->hasManyThrough(
            Inscription::class, // table finale
            Eleve::class,       // table intermédiaire
            'paren_id',         // FK sur eleves
            'eleve_id',         // FK sur inscriptions
            'id',               // PK parens
            'id'                // PK eleves
        );
    }

}
