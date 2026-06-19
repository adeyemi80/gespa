<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Matiere;
use App\Models\Trimestre; 
use App\Traits\CalculeMoyennes;
use App\Exports\NotesTemplateMultiSheetsExport;

class NoteImportWizard extends Component
{
   use WithFileUploads, CalculeMoyennes;

    /*
    |--------------------------------------------------------------------------
    | STEP WIZARD
    |--------------------------------------------------------------------------
    */
    public int $step = 1;

    /*
    |--------------------------------------------------------------------------
    | FILTRES STEP 1
    |--------------------------------------------------------------------------
    */
    public $cycle_id      = null;
    public $annee_id      = null;
    public $classe_id     = null;
    public $trimestre_id  = null;
    public $matiere_id    = null;
    public array $types   = [];

    /*
    |--------------------------------------------------------------------------
    | COLLECTIONS
    |--------------------------------------------------------------------------
    */
    public $classes   = [];
    public $matieres  = [];
    public $trimestres = [];

    /*
    |--------------------------------------------------------------------------
    | UPLOAD + PREVIEW
    |--------------------------------------------------------------------------
    */
    public $fichier = null;
    public array $preview = [];
    public int $invalid_count = 0;

    /*
    |--------------------------------------------------------------------------
    | RÉSULTAT FINAL
    |--------------------------------------------------------------------------
    */
    public int $imported_count = 0;
    public int $ignored_count  = 0;

    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */
    public function mount(): void
    {
        $this->classes   = collect();
        $this->matieres  = collect();
        $this->trimestres = collect();

        // Année en cours par défaut
        $annee = Annee::where('en_cours', true)->first();
        if ($annee) {
            $this->annee_id   = $annee->id;
            $this->trimestres = $annee->trimestres;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | WATCHERS
    |--------------------------------------------------------------------------
    */
    public function updatedCycleId($value): void
    {
        $this->classes   = $value
            ? Classe::where('cycle_id', $value)->orderBy('ordre')->get()
            : collect();

        $this->classe_id  = null;
        $this->matieres   = collect();
        $this->matiere_id = null;
    }

    public function updatedAnneeId($value): void
    {
        $annee = Annee::with('trimestres')->find($value);
        $this->trimestres  = $annee ? $annee->trimestres : collect();
        $this->trimestre_id = null;
    }

    public function updatedClasseId($value): void
    {
        $this->matieres   = $value
            ? Classe::with('matieres')->find($value)?->matieres ?? collect()
            : collect();

        $this->matiere_id = null;
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 1 → 2 : TÉLÉCHARGER MODÈLE
    |--------------------------------------------------------------------------
    */
    public function downloadTemplate()
    {
        $this->validate([
            'annee_id'    => 'required|exists:annees,id',
            'classe_id'   => 'required|exists:classes,id',
            'trimestre_id'=> 'required|exists:trimestres,id',
        ]);

        $classe    = \App\Models\Classe::findOrFail($this->classe_id);
        $annee     = Annee::findOrFail($this->annee_id);
        $trimestre = Trimestre::findOrFail($this->trimestre_id);

        $filename = 'modele_notes_'
            . str()->slug($classe->nom) . '_'
            . str()->slug($trimestre->nom) . '_'
            . str()->slug($annee->nom) . '.xlsx';

        return Excel::download(
            new NotesTemplateMultiSheetsExport($this->annee_id, $this->classe_id),
            $filename
        );
    }

    public function goToUpload(): void
    {
        $this->validate([
            'annee_id'    => 'required|exists:annees,id',
            'classe_id'   => 'required|exists:classes,id',
            'trimestre_id'=> 'required|exists:trimestres,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'types'       => 'required|array|min:1',
        ]);

        $this->step = 2;
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2 : PREVIEW DU FICHIER
    |--------------------------------------------------------------------------
    */
       public function previewFichier(): void
{
    $this->validate([
        'fichier' => 'required|file|mimes:xlsx,csv|max:5120',
    ]);

    $matiere    = Matiere::findOrFail($this->matiere_id);
    $excelArray = Excel::toArray([], $this->fichier->getRealPath());
    $sheetData  = $excelArray[0] ?? [];

    if (empty($sheetData)) {
        $this->addError('fichier', 'Fichier Excel vide.');
        return;
    }

    $this->preview       = [];
    $this->invalid_count = 0;

    $typesColonnes = [
        'interrogation1' => 3,
        'interrogation2' => 4,
        'interrogation3' => 5,
        'devoir1'        => 6,
        'devoir2'        => 7,
        'composition'    => 8,
    ];

    // Récupérer tous les matricules de la DB pour comparaison souple
    $elevesParMatricule = Eleve::all()->keyBy(function ($e) {
        return trim((string) $e->matricule);
    });

    foreach (array_slice($sheetData, 1) as $row) {
        // Conversion robuste du matricule lu par Excel
        $raw = $row[0] ?? '';
        if (is_int($raw) || is_float($raw)) {
            $matricule = rtrim(number_format($raw, 0, '', ''), '.0');
        } else {
            $matricule = trim((string) $raw);
        }

        if (empty($matricule)) continue;

        // Recherche dans la collection (évite les problèmes de type SQL)
        $eleve = $elevesParMatricule->get($matricule);
        if (!$eleve) continue;

        $inscription = Inscription::where([
            'eleve_id'  => $eleve->id,
            'classe_id' => $this->classe_id,
            'annee_id'  => $this->annee_id,
        ])->first();

        if (!$inscription) continue;

        foreach ($this->types as $type) {
            $colIndex = $typesColonnes[$type] ?? null;
            if ($colIndex === null) continue;

            $valBrute = $row[$colIndex] ?? '';
            if (is_int($valBrute) || is_float($valBrute)) {
                $valBrute = (string) $valBrute;
            } else {
                $valBrute = trim((string) $valBrute);
            }

            if ($valBrute === '') continue;

            $noteNum = is_numeric($valBrute) ? (float) $valBrute : null;
            $isValid = $noteNum !== null && $noteNum >= 0 && $noteNum <= 20;

            if (!$isValid) $this->invalid_count++;

            $this->preview[] = [
                'matricule'      => $matricule,
                'eleve'          => $eleve->nom . ' ' . $eleve->prenom,
                'inscription_id' => $inscription->id,
                'type'           => $type,
                'note'           => $noteNum ?? $valBrute,
                'matiere'        => $matiere->nom,
                'valid'          => $isValid,
            ];
        }
    }

    if (empty($this->preview)) {
        $this->addError('fichier', 'Aucun élève reconnu dans ce fichier.');
        return;
    }

    $this->step = 3;
}
    public function backToStep1(): void
    {
        $this->step    = 1;
        $this->fichier = null;
        $this->preview = [];
    }

    public function backToStep2(): void
    {
        $this->step    = 2;
        $this->preview = [];
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3 → 4 : SAUVEGARDE
    |--------------------------------------------------------------------------
    */
    public function sauvegarderNotes(): void
{
    if (empty($this->preview)) {
        session()->flash('error', 'Aucune donnée à sauvegarder.');
        return;
    }

    $this->imported_count = 0;
    $this->ignored_count  = 0;

    $grouped = collect($this->preview)->groupBy('inscription_id');

    foreach ($grouped as $inscriptionId => $lignes) {
        $note = Note::firstOrNew([
            'inscription_id' => $inscriptionId,
            'matiere_id'     => $this->matiere_id,
            'trimestre_id'   => $this->trimestre_id,
            'annee_id'       => $this->annee_id,
            'classe_id'      => $this->classe_id,
        ]);

        foreach ($lignes as $ligne) {
            $type = $ligne['type'];
            if ($ligne['valid']) {
                $note->$type = (float) $ligne['note'];
                $this->imported_count++;
            } else {
                $note->$type = null;
                $this->ignored_count++;
            }
        }

        // ✅ Recalcul des moyennes après affectation des notes
        $i1 = is_numeric($note->interrogation1) ? (float) $note->interrogation1 : null;
        $i2 = is_numeric($note->interrogation2) ? (float) $note->interrogation2 : null;
        $i3 = is_numeric($note->interrogation3) ? (float) $note->interrogation3 : null;
        $d1 = is_numeric($note->devoir1)        ? (float) $note->devoir1        : null;
        $d2 = is_numeric($note->devoir2)        ? (float) $note->devoir2        : null;

        $note->moyenne_interro = $this->calculerMoyenneInterro($i1, $i2, $i3);
        $note->moyenne_matiere = $this->calculerMoyenneMatiere($note->moyenne_interro, $d1, $d2);

        $note->save();
    }

    $this->reset(['fichier', 'preview', 'invalid_count']);
    $this->step = 4;
}
    /*
    |--------------------------------------------------------------------------
    | RESET COMPLET
    |--------------------------------------------------------------------------
    */
    public function recommencer(): void
    {
        $this->reset([
            'cycle_id', 'classe_id', 'matiere_id',
            'trimestre_id', 'types',
            'fichier', 'preview',
            'invalid_count', 'imported_count', 'ignored_count',
        ]);

        $this->classes   = collect();
        $this->matieres  = collect();
        $this->step      = 1;
    }

    /* 
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */
    public function render()
    {
        return view('livewire.note-import-wizard', [
            'cycles' => Cycle::all(),
            'annees' => Annee::orderBy('nom')->get(),
        ]);
    }
}