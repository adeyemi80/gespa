<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Moyenne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PassageController extends Controller
{
    /**
     * Prévisualisation du passage scolaire
     */
    public function index(Request $request)
    {
    
        return view('passages.index');
    }

    public function annuler(Request $request)
    {
    
        return view('passages.annuler');
    }

    /**
     * Passage global
     */
    


}