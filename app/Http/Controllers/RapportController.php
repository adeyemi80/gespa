<?php

namespace App\Http\Controllers;

use App\Models\Investisseur;
use App\Models\Investissement;
use App\Models\Versement;
use App\Models\Benefice;
use App\Models\Repartition;
use App\Models\PaiementBenefice;
use App\Models\RetraitCapital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RapportController extends Controller
{

    /**
     * Tableau général des rapports
     */
    public function index()
    {
        return view('investissements.rapports.index');
    }



    /**
     * Export statistiques générales
     */
    public function statistiques()
    {


        $data = [

            'investisseurs'=>
                Investisseur::count(),


            'capital'=>
                Investissement::sum('montant'),


            'versements'=>
                Versement::sum('montant'),


            'benefices'=>
                Benefice::sum('montant'),


            'benefices_payes'=>
                PaiementBenefice::sum('montant'),


            'retraits'=>
                RetraitCapital::sum('montant'),

        ];



        return response()->json($data);

    }

}