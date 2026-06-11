<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return view('tableau.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tableau.create');
    }

    public function create1()
    {
        return view('tableau.show');
    }
     public function create2()
    {
        return view('tableau.bulletin');
    }
    public function create3()
    {
        return view('tableau.pedagogie');
    }
    public function create4()
    {
        return view('tableau.conduite');
    }
 public function create5()
    {
        return view('tableau.paiement');
    }
     public function create6()
    {
        return view('tableau.parent');
    }
     public function prendre()
    {
        return view('tableau.examen');
    }
    public function emplois()
    {
        return view('tableau.emplois');
    }
    public function planning()
    {
        return view('tableau.planning');
    }
    public function annees()
    {
        return view('tableau.annees');
    }
    public function utilisateur()
    {
        return view('tableau.utilisateur');
    }
     public function accueil()
    {
        return view('tableau.accueil');
    }
     public function progtd()
    {
        return view('tableau.progtd');
    }
     public function inscription()
    {
        return view('tableau.inscription');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $tableaus = Tableau::all();
         return view('tableaus.now', compact('tableaus'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
