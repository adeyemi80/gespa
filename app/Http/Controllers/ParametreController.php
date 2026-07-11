<?php

namespace App\Http\Controllers;

use App\Models\ParametreInvestissement;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    /**
     * Affichage de la liste des paramètres
     */
    public function index(Request $request)
    {
        return view(
            'investissements.parametres.index');
    }


}