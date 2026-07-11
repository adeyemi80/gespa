<?php

namespace App\Http\Controllers;

use App\Models\Repartition;
use App\Models\Benefice;
use App\Models\Investissement;
use App\Models\Investisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepartitionController extends Controller
{

    /**
     * Liste des répartitions
     */
    public function index(Request $request)
    {

        return view(
            'investissements.repartitions.index');
    }


}