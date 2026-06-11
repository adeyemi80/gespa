<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PrevisualisationImport;
use Maatwebsite\Excel\Facades\Excel;


class ImportPreviewController extends Controller
{
    public function previsualiser(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:xlsx,csv,xls',
            'type' => 'required|in:eleves,parents,notes',
        ]);

        // Classe et année optionnelles selon le type
        $classeId = $request->classe_id;
        $anneeId = $request->annee_id;
        $type = $request->type;

        $import = new PrevisualisationImport($type);
        Excel::import($import, $request->file('fichier'));

        return view('eleves.previsualiser', [
            'rows' => $import->validatedRows,
            'classe_id' => $classeId,
            'annee_id' => $anneeId,
            'fichier' => base64_encode(file_get_contents($request->file('fichier'))),
            'extension' => $request->file('fichier')->getClientOriginalExtension(),
            'type' => $type,
        ]);
    }
}
