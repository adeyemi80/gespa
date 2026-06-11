<?php

namespace App\Services;

use App\Models\Annee;
use App\Models\Classe;
// app/Services/AnneeService.php
class AnneeService 
{
    public static function classesPourAnnee($anneeId) 
    {
        return Annee::find($anneeId)->classesActives;
    }
}
