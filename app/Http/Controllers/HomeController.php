<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
       $role=Auth::user()->role;
        if($role=='admin')
        {
            return view('show.show');
        }
        if($role=='secretaire')
        {
            return view('tableau.create');
        }
        if($role=='comptable')
        {
            return view('show.create');
        }
        if($role=='enseignant')
        {
            return view('bord.index');
        }
        if($role=='parent')
        {
            return redirect()->route('parens.dashboard');
        }
        if($role=='directeur')
        {
            return view('bord.create');
        }
        if($role=='censeur')
        {
            return view('bord.show');
        }
        if($role=='surveillant')
        {
            return view('tableau.index');
        }
        else
        {
            return view('dashboard');
        } 
    }
}
