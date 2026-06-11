<?php

namespace App\Helpers;

class MontantHelper
{
    public static function enLettres($nombre)
    {
        $fmt = new \NumberFormatter("fr", \NumberFormatter::SPELLOUT);
        return ucfirst($fmt->format($nombre));
    }
}