<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\Epreuve;
use App\Models\Classe;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreEpreuveRequest;
use App\Http\Requests\UpdateEpreuveRequest;

class EpreuveController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index()
    {
        $epreuves = Epreuve::all();
        return view('epreuves.index', compact('epreuves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $classes = Classe::all();
        return view('epreuves.create', compact('classes'));
    }

    /**
     * Store a newly created resource  storage.
     */
    public function store(StoreEpreuveRequest $request) : RedirectResponse
    {
        $this->validate($request, [
            'trimestre' => ['required', 'string', 'max:255'],
            'matiere' => ['required', 'string', 'max:255'],
            'nature' => ['inrequired', 'string', 'max:255'],
            'file' => ['required', 'mimes:pdf,docx,doc,odt'],
            'classe_id' => ['required', 'integer', 'max:255'],
            
            
        ]);
    
        $epreuve = new Epreuve;
        $epreuve->trimestre = $request->trimestre;
        $epreuve->matiere = $request->matiere;
        $epreuve->nature = $request->nature;
        $epreuve->classe_id = $request->classe_id;
        $fileName = time().".".$request->file->extension();
        $request->file->move(public_path("epreuves"), $fileName);
         $epreuve->file = $fileName;
        $epreuve->save();
        return redirect()->route('epreuves.index')
                ->withSuccess('Une Nouvelle Epreuve a été ajoutée avec Succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($epreuve_id) : View
    {
        $epreuve = Epreuve::find($epreuve_id);
        return view('epreuves.show', [
        'epreuve' => $epreuve
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($epreuve_id) : View
   {
    $classes = Classe::all();
    $epreuve = Epreuve::find($epreuve_id);
        return view('epreuve.edit', compact( 'classes'), ['epreuve' => $epreuve]);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEpreuveRequest $request, $epreuve_id) : RedirectResponse
    { 
        
        $epreuve = Epreuve::find($epreuve_id);
        $epreuve->update($request->all());
        return redirect()->back()
                ->withSuccess('L\'epreuve a été modifiée avec Succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($epreuve_id) : RedirectResponse
    {
        $epreuve = Epreuve::find($epreuve_id);
        $epreuve->delete();
        return redirect()->route('epreuves.index')
                ->withSuccess('L\'épreuve a été Supprimée avec Succès');
    }

}
