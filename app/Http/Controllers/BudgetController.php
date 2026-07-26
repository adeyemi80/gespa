<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Categorie;
use App\Models\Annee;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {

        return view('budgets.index');
    }

    public function recettes()
    {

        return view('budgets.recettes');
    }

    public function depenses()
    {

        return view('budgets.depenses');
    }

    public function synthese()
    {

        return view('budgets.synthese');
    }
    
}
