<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class TdController extends Controller
{
    
   public function index()
{

    return view('td.index');
}

    public function dirige()
{

    return view('td.dirige');
}

}
