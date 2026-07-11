<?php

namespace App\Http\Controllers;

use App\Models\Investissement;
use App\Models\Investisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestissementController extends Controller
{

    /**
     * Liste des investissements
     */
    public function index(Request $request)
    {
      return view('investissements.index');
    }




}