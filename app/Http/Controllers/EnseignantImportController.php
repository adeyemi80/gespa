<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EnseignantImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class EnseignantImportController extends Controller
{
    public function showForm()
    {
        return view('enseignants.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:xlsx,csv',
        ]);

        $import = new EnseignantImport();
        Excel::import($import, $request->file('fichier'));

        if (!empty($import->erreurs)) {
            Storage::put('temp/erreurs_enseignants.json', json_encode($import->erreurs));
            return redirect()->route('enseignants.import.erreurs')->with('erreurs', $import->erreurs);
        }

        return redirect()->route('enseignants.index')->with('success', 'Importation réussie.');
    }

    public function showErreurs()
    {
        $erreurs = json_decode(Storage::get('temp/erreurs_enseignants.json'), true);
        return view('enseignants.erreurs', compact('erreurs'));
    }
}
