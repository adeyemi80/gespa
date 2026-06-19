<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Trimestre;
use App\Models\Annee;
use App\Services\MoyenneService;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ImportationNote;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Exports\NotesTemplateMultiFeuillesExport;

class ImportationNoteController extends Controller
{
    
    public function importer()
    {
        return view('notes.import', [
            'classes' => Classe::all(),
            'annees' => DB::table('annees')->get(),
            'matieres' => DB::table('matieres')->get(),
            'trimestres' => DB::table('trimestres')->get(),
        ]);
    }

   public function downloadTemplate(Request $request)
{
    // Récupérer les paramètres (GET ou POST)
    $classe_id = $request->input('classe_id');
    $trimestre_id = $request->input('trimestre_id');

    // Vérification des paramètres
    if (!$classe_id || !$trimestre_id) {
        return redirect()->back()->with('error', 'Veuillez sélectionner une classe et un trimestre.');
    }

    // Log pour débogage
    \Log::info('downloadTemplate appelé', [
        'classe_id' => $classe_id,
        'trimestre_id' => $trimestre_id
    ]);

    // Récupération des modèles
    $classe = Classe::find($classe_id);
    $trimestre = Trimestre::find($trimestre_id);

    if (!$classe || !$trimestre) {
        return redirect()->back()->with('error', 'Classe ou trimestre invalide.');
    }

    // Vérifier qu’il y a au moins un élève dans la classe
    $nombreEleves = $classe->eleves()->count();
    if ($nombreEleves === 0) {
        return back()->with('error', "La classe « {$classe->nom} » ne contient aucun élève, impossible de télécharger le fichier.");
    }

    // Nom de fichier dynamique
    $filename = 'Notes_classe_' . Str::slug($classe->nom) . '_' . Str::slug($trimestre->nom) . '.xlsx';

    // Télécharger le fichier Excel multi-feuilles
    return Excel::download(new NotesTemplateMultiFeuillesExport($classe_id, $trimestre_id, $annee_id), $filename);
}

public function previsualiser(Request $request)
{
    $validated = $request->validate([
        'annee_id'     => 'required|exists:annees,id',
        'trimestre_id' => 'required|exists:trimestres,id',
        'matiere_id'   => 'required|exists:matieres,id',
        'classe_id'    => 'required|exists:classes,id',
        'fichier'      => 'required|file|mimes:xlsx,csv',
    ]);

    $trimestre = Trimestre::find($validated['trimestre_id']);
    $classe    = Classe::find($validated['classe_id']);
    $matiere   = Matiere::find($validated['matiere_id']);

    // Nom du fichier
    $fichierNom = strtolower($request->file('fichier')->getClientOriginalName());

    // Normalisation
    $nomTrimestreSlug = Str::slug($trimestre->nom);
    $nomClasseSlug    = Str::slug($classe->nom);

    // Vérification nom fichier
    if (!str_contains(Str::slug($fichierNom), $nomTrimestreSlug)) {
        return back()->with('error', "Le fichier sélectionné ne correspond pas au trimestre choisi ({$trimestre->nom}).");
    }
    if (!str_contains(Str::slug($fichierNom), $nomClasseSlug)) {
        return back()->with('error', "Le fichier sélectionné ne correspond pas à la classe choisie ({$classe->nom}).");
    }

    // Charger Excel
    $spreadsheet = IOFactory::load($request->file('fichier')->getPathname());
    $sheet       = $spreadsheet->getSheetByName($matiere->nom);

    if (!$sheet) {
        return back()->with('error', "La feuille Excel pour la matière « {$matiere->nom} » est introuvable.");
    }

    $donnees = $sheet->toArray();
    $valides = [];
    $erreurs = [];

    foreach ($donnees as $index => $ligne) {
        if ($index === 0) continue; // ignorer l'entête
        [$matricule, $nom, $prenom, $moyenne_interro, $devoir1, $devoir2] = array_map('trim', $ligne);

        // Vérification basique
        if (!$matricule || !is_numeric($moyenne_interro) || !is_numeric($devoir1) || !is_numeric($devoir2)) {
            $erreurs[] = [
                'ligne'   => compact('matricule', 'nom', 'prenom'),
                'message' => 'Champs manquants ou invalides'
            ];
            continue;
        }

        // Notes hors limites
        if ($moyenne_interro < 0 || $moyenne_interro > 20 ||
            $devoir1 < 0 || $devoir1 > 20 ||
            $devoir2 < 0 || $devoir2 > 20) {
            $erreurs[] = [
                'ligne'   => compact('matricule', 'nom', 'prenom'),
                'message' => 'Notes hors limites'
            ];
            continue;
        }

        // Vérification inscription (année + classe)
        $inscription = \App\Models\Inscription::with('eleve')
            ->whereHas('eleve', fn($q) => $q->where('matricule', $matricule))
            ->where('annee_id', $validated['annee_id'])
            ->where('classe_id', $validated['classe_id'])
            ->first();

        if (!$inscription) {
            $erreurs[] = [
                'ligne'   => compact('matricule', 'nom', 'prenom'),
                'message' => "Aucune inscription trouvée pour {$matricule} dans cette classe et année"
            ];
            continue;
        }

        // Vérification doublon
        $existe = DB::table('notes')->where([
            'inscription_id' => $inscription->id,
            'matiere_id'     => $validated['matiere_id'],
            'trimestre_id'   => $validated['trimestre_id'],
        ])->exists();

        if ($existe) {
            $erreurs[] = [
                'ligne'   => compact('matricule', 'nom', 'prenom'),
                'message' => 'Note déjà importée'
            ];
            continue;
        }

        // Ligne valide
        $valides[] = [
            'inscription_id'  => $inscription->id,
            'matricule'       => $matricule,
            'nom'             => $inscription->eleve->nom,
            'prenom'          => $inscription->eleve->prenom,
            'moyenne_interro' => $moyenne_interro,
            'devoir1'         => $devoir1,
            'devoir2'         => $devoir2,
        ];
    }

    return view('notes.previsualisation', [
        'valides'      => $valides,
        'erreurs'      => $erreurs,
        'annee_id'     => $validated['annee_id'],
        'trimestre_id' => $validated['trimestre_id'],
        'matiere_id'   => $validated['matiere_id'],
        'classe_id'    => $validated['classe_id'],
        'fichier_nom'  => $fichierNom,
    ]);
}

public function inserer(Request $request, MoyenneService $moyenneService)
{    dd(request()->all());
    // Validation
    $request->validate([
        'matiere_id'   => 'required|exists:matieres,id',
        'trimestre_id' => 'required|exists:trimestres,id',
        'annee_id'     => 'required|exists:annees,id',
        'valides'      => 'required|json',
    ]);

    $matiereId   = (int) $request->matiere_id;
    $trimestreId = (int) $request->trimestre_id;
    $anneeId     = (int) $request->annee_id;

    $matiere = Matiere::find($matiereId);
    $valides = json_decode($request->valides, true);

    if (!is_array($valides)) {
        return back()->with('error', 'Les données envoyées sont invalides.');
    }

    // Supprimer les anciennes notes pour ce trimestre/année/matière
    Note::where('matiere_id', $matiereId)
        ->where('trimestre_id', $trimestreId)
        ->where('annee_id', $anneeId)
        ->delete();

    $lignesRejetees = [];

    foreach ($valides as $noteData) {
        // Retrouver l'élève par matricule
        $eleve = Eleve::where('matricule', $noteData['matricule'] ?? null)->first();
        if (!$eleve) {
            $lignesRejetees[] = array_merge($noteData, ['erreur' => 'Élève non trouvé']);
            continue;
        }

        // Retrouver l'inscription
        $inscription = Inscription::where('eleve_id', $eleve->id)
            ->where('annee_id', $anneeId)
            ->first();
        if (!$inscription) {
            $lignesRejetees[] = array_merge($noteData, ['erreur' => 'Inscription non trouvée pour l’année']);
            continue;
        }

        // Notes
        $moyenne_interro = floatval($noteData['moyenne_interro'] ?? 0);
        $devoir1 = floatval($noteData['devoir1'] ?? 0);
        $devoir2 = floatval($noteData['devoir2'] ?? 0);

        if ($moyenne_interro < 0 || $moyenne_interro > 20 || $devoir1 < 0 || $devoir1 > 20 || $devoir2 < 0 || $devoir2 > 20) {
            $lignesRejetees[] = array_merge($noteData, ['erreur' => 'Notes hors intervalle [0,20]']);
            continue;
        }

        // Calcul moyenne
        $moyenne_matiere = round(($moyenne_interro + $devoir1 + $devoir2) / 3, 2);
        $appreciation = $this->getAppreciation($moyenne_matiere);

        // Insertion via Eloquent
        Note::create([
            'inscription_id'  => $inscription->id,
            'classe_id'       => $inscription->classe_id,
            'matiere_id'      => $matiereId,
            'trimestre_id'    => $trimestreId,
            'annee_id'        => $anneeId,
            'moyenne_interro' => $moyenne_interro,
            'devoir1'         => $devoir1,
            'devoir2'         => $devoir2,
            'moyenne_matiere' => $moyenne_matiere,
            'appreciation'    => $appreciation,
        ]);

        // Mise à jour des moyennes globales
        $moyenneService->mettreAJourMoyennes($inscription->id, $anneeId, $trimestreId);
    }

    return view('notes.import', [
        'success' => 'Notes de la matière « '.$matiere->nom.' » insérées avec succès !',
        'lignesRejetees' => $lignesRejetees
    ]);
}

public function resetCompteur()
    {
        // Remettre le compteur à zéro
        session()->forget('matieresImportees');
        return redirect()->route('notes.index')->with('info', 'Compteur réinitialisé.');
    }
    
    private function getAppreciation(float $moyenne): string
     {
    if ($moyenne >= 0 && $moyenne < 2) {
        return 'Nul';
    } elseif ($moyenne >= 2 && $moyenne < 4) {
        return 'Médiocre';
    } elseif ($moyenne >= 4 && $moyenne < 6) {
        return 'Très Faible';
    } elseif ($moyenne >= 6 && $moyenne < 8) {
        return 'Faible';
    } elseif ($moyenne >= 8 && $moyenne < 10) {
        return 'Insuffisant';
    } elseif ($moyenne >= 10 && $moyenne < 12) {
        return 'Passable';
    } elseif ($moyenne >= 12 && $moyenne < 14) {
        return 'Assez Bien';
    } elseif ($moyenne >= 14 && $moyenne < 16) {
        return 'Bien';
    } elseif ($moyenne >= 16 && $moyenne < 18) {
        return 'Très Bien';
    } elseif ($moyenne >= 18 && $moyenne <= 20) {
        return 'Excellent';
    } else {
        return 'Valeur invalide';
    }
     }

    
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Si tu oublies ceci, la vue n’aura pas $inscriptions
    $inscriptions = Inscription::all();
    $notes = Note::paginate(10);
    return view('notes.index', compact('notes', 'inscriptions'));
}


    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $inscriptions = Inscription::with(['eleve','classe','annee'])->get();
    $matieres = Matiere::all();
    $trimestres = Trimestre::all();
    $annees = Annee::all();

    $note = null; // pour éviter l'erreur dans le Blade

    return view('notes.form', compact('inscriptions','matieres','trimestres','annees','note'));
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
    public function show(Note $note)
{   
    // Charger l'inscription liée et ses relations
    $note->load('inscription.eleve', 'inscription.classe', 'inscription.annee', 'matiere', 'trimestre');

    return view('notes.show', [
        'note' => $note
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $classes = Classe::all();
        $eleves = Eleve::all();
         $annees = Annee::all();
          $matieres = Matiere::all();
           $trimestres = Trimestre::all();
        return view('notes.create', compact('classes', 'eleves', 'annees', 'matieres', 'trimestres'));
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
    public function destroy(note $note)
    {
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Note supprimée avec succès.');
    }
}
