<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Matiere;

class AjaxController extends Controller
{
    public function trimestres($annee)
{
    $annee = Annee::findOrFail($annee);

    $trimestres = $annee->trimestres()
                        ->select('trimestres.id as id', 'trimestres.nom')
                        ->get();

    return response()->json($trimestres);
}

   public function classes($annee)
{
    $annee = Annee::findOrFail($annee);

    $classes = $annee->classes()
                     ->select('classes.id as id', 'classes.nom')
                     ->get();

    return response()->json($classes);
}

   public function matieres($classe)
{
    $classe = Classe::findOrFail($classe);

    $matieres = $classe->matieres()->get();

    return response()->json($matieres);
}
public function getClassesActives($anneeId)
{
    return Classe::whereHas('annees', function ($q) use ($anneeId) {
            $q->where('annee_id', $anneeId);
        })
        ->where('cycle_id', 3) // ✅ FILTRE ICI
        ->orderBy('nom')
        ->get();
}



}