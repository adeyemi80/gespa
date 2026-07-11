<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreInvestissement extends Model
{
    use HasFactory;

    protected $table = 'parametres_investissements';

    protected $fillable = [

        'cle',

        'libelle',

        'valeur',

        'type',

        'description',

        'actif',

    ];

    protected $casts = [

        'actif' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Méthodes utilitaires
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne la valeur d'un paramètre.
     */
    public static function valeur($cle, $defaut = null)
    {
        return static::where('cle', $cle)
            ->where('actif', true)
            ->value('valeur') ?? $defaut;
    }

    /**
     * Crée ou met à jour un paramètre.
     */
    public static function definir(
        string $cle,
        string $libelle,
        $valeur,
        string $type = 'texte'
    ) {
        return static::updateOrCreate(
            ['cle' => $cle],
            [
                'libelle' => $libelle,
                'valeur' => $valeur,
                'type' => $type,
                'actif' => true,
            ]
        );
    }
}