<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamenBlanc;
use App\Models\ParticipantExamen;
use App\Models\NoteExamen;
use App\Models\Matiere;
use App\Exports\NotesExamenTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
 use Carbon\Carbon;

class NoteExamenController extends Controller
{

public function index()
{
    // Exemple simple : afficher la liste des examens blancs
    $examens = ExamenBlanc::with('classes')->get();
    return view('examens.notes.index', compact('examens'));
}
    // Télécharger le modèle

public function downloadTemplate($examenId)
{
    //dd($request->all());
    $examen = ExamenBlanc::findOrFail($examenId);

    // Transformer la date string en objet Carbon
    $dateDebut = Carbon::parse($examen->date_debut);
    $annee = $dateDebut->format('Y');
    $mois  = $dateDebut->format('m');

    $type = strtoupper(str_replace(' ', '', $examen->type));
    $filename = "NotesExamenBlanc_{$type}_{$mois}_{$annee}.xlsx";

    return Excel::download(new NotesExamenTemplateExport($examen), $filename);
}

    // Formulaire d'import
    public function importForm($examenId)
    {
        $examen = ExamenBlanc::with('participants.inscription.eleve', 'classes.matieres')
                    ->findOrFail($examenId);
        return view('examens.notes.import', compact('examen'));
    }

    // Prévisualisation
    public function preview(Request $request)
    {
        $file = $request->file('file');
        $data = Excel::toArray([], $file)[0];

        $header = $data[0];
        $rows = array_slice($data, 1);

       $errors = [];

foreach ($rows as $i => $row) {
    foreach ($row as $j => $cell) {
        // Les notes commencent à la colonne 3 (index 0-based)
        if ($j >= 3 && $cell !== '') {
            // Normaliser la cellule (virgule → point, trim)
            $cell = trim(str_replace(',', '.', $cell));

            if (!is_numeric($cell) || $cell < 0 || $cell > 20) {
                $errors[$i][$j] = 'Note invalide';
            }
        }
    }
}

        return view('examens.notes.preview', [
            'header' => $header,
            'rows' => $rows,
            'errors' => $errors,
            'examen_id' => $request->examen_id
        ]);
    }

   public function import(Request $request)
{
    $data = $request->data;

    $examen = ExamenBlanc::with('classes.matieres')->findOrFail($request->examen_id);

    $matieres = $examen->classes->flatMap->matieres->unique('id');

    // 🔹 1. Enregistrer les notes
    foreach ($data as $row) {

        $participant = ParticipantExamen::where('numero_table', $row['numero_table'])
            ->where('examen_blanc_id', $examen->id)
            ->first();

        if (!$participant) continue;

        foreach ($matieres as $matiere) {
            $noteValue = $row[$matiere->nom] ?? null;

            // Normalisation
            $noteValue = trim(str_replace(',', '.', $noteValue));

            if ($noteValue !== '' && is_numeric($noteValue) && $noteValue >= 0 && $noteValue <= 20) {

                NoteExamen::updateOrCreate(
                    [
                        'participant_id' => $participant->id,
                        'matiere_id' => $matiere->id
                    ],
                    [
                        'note' => $noteValue
                    ]
                );
            }
        }
    }

    // 🔥 2. Calcul des moyennes
    $participants = ParticipantExamen::with('notes.matiere')
        ->where('examen_blanc_id', $examen->id)
        ->get();

    foreach ($participants as $participant) {

        $totalCoef = 0;
        $totalNoteCoef = 0;

        foreach ($participant->notes as $note) {
            $coef = $note->matiere->coefficient ?? 1;

            $totalCoef += $coef;
            $totalNoteCoef += $note->note * $coef;
        }

        $participant->moyenne = $totalCoef > 0
            ? round($totalNoteCoef / $totalCoef, 2)
            : null;

        $participant->save();
    }

    return redirect()->route('examens-blancs.index')
        ->with('success', 'Notes importées et moyennes calculées avec succès !');
}


}