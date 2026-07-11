<?php

namespace App\Http\Controllers;

use App\Models\Benefice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficeController extends Controller
{
    /**
     * Liste des bénéfices
     */
    public function index(Request $request)
    {
        
        return view(
            'investissements.benefices.index');
    }



}