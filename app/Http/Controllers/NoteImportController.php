<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Matiere;
use App\Models\Trimestre;
use App\Exports\NotesTemplateMultiSheetsExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class NoteImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

public function index()
{
    $cycles = Cycle::all();
    $annees = Annee::with('trimestres')->orderBy('nom')->get();
    $classes = Classe::with('matieres')->orderByNiveau('nom')->get();
    return view('notes.import2', compact('annees', 'classes', 'cycles'));
}


public function downloadTemplate(Request $request)
{ //dd('Controller atteint', $request->all());
    $request->validate([
        'annee_id'     => 'required|exists:annees,id',
        'classe_id'    => 'required|exists:classes,id',
        'trimestre_id' => 'required|exists:trimestres,id',
    ]);

    $classe    = Classe::findOrFail($request->classe_id);
    $annee     = Annee::findOrFail($request->annee_id);
    $trimestre = Trimestre::findOrFail($request->trimestre_id);

    $filename = 'modele_notes_' .
        str()->slug($classe->nom) . '_' .
        str()->slug($trimestre->nom) . '_' .
        str()->slug($annee->nom) . '.xlsx';

    return Excel::download(
        new NotesTemplateMultiSheetsExport(
            $request->annee_id,
            $request->classe_id
        ),
        $filename
    );
}


public function preview(Request $request)
{
    $request->validate([
        'annee_id' => 'required|exists:annees,id',
        'trimestre_id' => 'required|exists:trimestres,id',
        'classe_id' => 'required|exists:classes,id',
        'matiere_id' => 'required|exists:matieres,id',
        'types' => 'required|array|min:1',
        'file' => 'required|file|mimes:xlsx,csv',
    ]);

    $matiere = Matiere::findOrFail($request->matiere_id);
    $excelArray = Excel::toArray([], $request->file('file'));
    
    // Prendre la 1ère feuille
    $sheetData = $excelArray[0] ?? null;
    
    if (!$sheetData || empty($sheetData)) {
        return back()->with('error', 'Fichier Excel vide.');
    }

    $preview = [];
    $invalidNotes = 0;
    
    foreach (array_slice($sheetData, 1) as $row) {
        $matricule = trim($row[0] ?? '');
        if (empty($matricule)) continue;
        
        $eleve = Eleve::where('matricule', $matricule)->first();
        if (!$eleve) continue;
        
        $inscription = Inscription::where([
            'eleve_id' => $eleve->id,
            'classe_id' => $request->classe_id,
            'annee_id' => $request->annee_id
        ])->first();
        
        if (!$inscription) continue;

        $notes = [
            'interrogation1' => trim($row[3] ?? ''),
            'interrogation2' => trim($row[4] ?? ''),
            'interrogation3' => trim($row[5] ?? ''),
            'devoir1' => trim($row[6] ?? ''),
            'devoir2' => trim($row[7] ?? ''),
            'composition' => trim($row[8] ?? ''),
        ];

        foreach ($request->types as $type) {
            $noteValue = $notes[$type] ?? '-';
            
            // 🔥 TOUTES les notes (valides + invalides) avec flag
            if ($noteValue !== '-') {
                $noteNum = is_numeric($noteValue) ? (float) $noteValue : 0;
                $isValid = is_numeric($noteValue) && $noteNum >= 0 && $noteNum <= 20;
                
                if (!$isValid) {
                    $invalidNotes++;
                }
                
                $preview[] = [
                    'matricule' => $matricule,
                    'eleve' => $eleve->nom . ' ' . $eleve->prenom,
                    'inscription_id' => $inscription->id,
                    'type' => $type,
                    'note' => $noteNum,
                    'matiere' => $matiere->nom,
                    'valid' => $isValid
                ];
            }
        }
    }

    // Alerte si notes invalides
    if ($invalidNotes > 0) {
        session()->flash('warning', "⚠️ {$invalidNotes} notes hors 0-20 détectées (visibles en rouge)");
    }

    // Stocker matière pour store()
    $request->session()->put('matiere_id', $request->matiere_id);

    return view('notes.preview', [
        'preview' => $preview,
        'inputs' => $request->only(['annee_id', 'trimestre_id', 'classe_id', 'matiere_id', 'types']),
        'invalid_notes_count' => $invalidNotes
    ]);
}


public function store(Request $request)
{
    $request->validate([
        'annee_id'     => 'required|exists:annees,id',
        'trimestre_id' => 'required|exists:trimestres,id',
        'classe_id'    => 'required|exists:classes,id',
        'data'         => 'required|array|min:1',
    ]);

    $matiere_id = $request->session()->get('matiere_id');
    if (!$matiere_id) {
        return back()->with('error', '⚠️ Matière non trouvée. Recommencez l\'import.');
    }

    $imported = 0;
    $invalidNotes = 0;

    foreach ($request->data as $index => $row) {
        $inscription_id = $row['inscription_id'] ?? null;
        if (!$inscription_id) continue;

        // Créer/Mettre à jour la note
        $note = Note::firstOrNew([
            'inscription_id' => $inscription_id,
            'matiere_id'     => $matiere_id,
            'trimestre_id'   => $request->trimestre_id,
            'annee_id'       => $request->annee_id,
            'classe_id'      => $request->classe_id,
        ]);

        // 🔥 VALIDATION 0-20 + Sauvegarde
        foreach ($row as $type => $value) {
            if (in_array($type, ['interrogation1','interrogation2','interrogation3','devoir1','devoir2','composition'])) {
                // Vérifier 0-20
                if (is_numeric($value) && $value !== '' && $value >= 0 && $value <= 20) {
                    $note->$type = (float) $value;
                } else {
                    $note->$type = null; // Note invalide → null
                    $invalidNotes++;
                }
            }
        }

        $note->save();
        $imported++;
    }

    // Message final avec stats
    $message = "✅ {$imported} notes traitées";
    if ($invalidNotes > 0) {
        $message .= " | ⚠️ {$invalidNotes} notes hors 0-20 ignorées";
    }

    return redirect()
        ->route('notes.import.index')
        ->with('success', $message);
}

   


}
