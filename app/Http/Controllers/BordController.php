<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bord.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('bord.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        return view('store.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('bord.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
       return view('bord.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
       return view('bord.update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        return view('bord.desroy');
    }
}
