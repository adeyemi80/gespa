<?php
// app/Helpers/NumberHelper.php
namespace App\Helpers;

class NumberHelper 
{
    public static function number_format_fr($number)
    {
        return number_format($number, 0, ',', ' ');
    }
}
