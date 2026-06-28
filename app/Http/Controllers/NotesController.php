<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Trimestre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotesTemplateMultiFeuillesExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class NotesController extends Controller
{

     public function index(Request $request)
{
    // ── Données initiales pour les selects ────────────────────────────────
    $annees     = Annee::orderByDesc('id')->get();
    $cycles     = Cycle::orderBy('id')->get();
    $trimestres = Trimestre::orderBy('id')->get();

    // Classes : filtrées par cycle si déjà sélectionné (pour conserver l'état après submit)
    $classes = $request->filled('cycle_id')
        ? Classe::where('cycle_id', $request->cycle_id)->orderBy('ordre')->get()
        : Classe::orderBy('ordre')->get();

    // Matières : filtrées par classe si déjà sélectionnée (pour conserver l'état après submit)
    $matieres = $request->filled('classe_id')
        ? Matiere::whereHas('classes', fn($q) => $q->where('classe_id', $request->classe_id))->orderBy('nom')->get()
        : Matiere::orderBy('nom')->get();

    // ── Construction de la query ──────────────────────────────────────────
    $query = Note::with([
            'inscription.eleve',
            'inscription.annee',
            'matiere',
            'classe',
            'trimestre',
        ])
        ->latest();

    if ($request->filled('annee_id')) {
        $query->where('annee_id', $request->annee_id);
    }

    if ($request->filled('trimestre_id')) {
        $query->where('trimestre_id', $request->trimestre_id);
    }

    if ($request->filled('cycle_id')) {
        $query->whereHas('classe', fn($q) =>
            $q->where('cycle_id', $request->cycle_id)
        );
    }

    if ($request->filled('classe_id')) {
        $query->where('classe_id', $request->classe_id);
    }

    if ($request->filled('matiere_id')) {
        $query->where('matiere_id', $request->matiere_id);
    }

    $notes = $query->paginate(50)->withQueryString();

    return view('notes.index', compact(
        'notes',
        'annees',
        'cycles',
        'classes',
        'trimestres',
        'matieres',
    ));
}

    public function create()
{
    $annees     = Annee::orderBy('nom')->get();
    $classes = Classe::orderByNiveau()->get();
    $inscriptions = Inscription::all();
    $matieres   = Matiere::orderBy('nom')->get();
    $trimestres = Trimestre::orderBy('ordre')->get();

    return view('notes.create', compact(
        'annees',
        'classes',
         'inscriptions',
        'matieres',
        'trimestres'
    ));
}
public function moyennes()
{

    return view('notes.moyennes');
}

public function update(Request $request, $id)
{
    $request->validate([
        'inscription_id'   => 'required|exists:inscriptions,id',
        'matiere_id'       => 'required|exists:matieres,id',
        'trimestre_id'     => 'required|exists:trimestres,id',

        'moyenne_interro'  => 'nullable|numeric|min:0|max:20',
        'devoir1'          => 'nullable|numeric|min:0|max:20',
        'devoir2'          => 'nullable|numeric|min:0|max:20',
    ]);

    $note = \App\Models\Note::findOrFail($id);

    $note->update([
        'inscription_id'  => $request->inscription_id,
        'matiere_id'      => $request->matiere_id,
        'trimestre_id'    => $request->trimestre_id,
        'moyenne_interro' => $request->moyenne_interro,
        'devoir1'         => $request->devoir1,
        'devoir2'         => $request->devoir2,
    ]);

    return redirect()
        ->route('notes.index')
        ->with('success', 'Note mise à jour avec succès');
}

public function edit($id)
{
    $note = \App\Models\Note::findOrFail($id);

    $inscriptions = \App\Models\Inscription::with(['eleve', 'classe', 'annee'])->get();
    $matieres     = \App\Models\Matiere::all();
    $trimestres   = \App\Models\Trimestre::all();

    return view('notes.edit', compact(
        'note',
        'inscriptions',
        'matieres',
        'trimestres'
    ));
}
public function show($id)
{
    $note = Note::findOrFail($id);
    return view('notes.show', compact('note'));
}

/**
     * Supprime une note
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('notes.index')
                         ->with('success', 'Note supprimée avec succès.');
    }
    /**
     * Affiche le formulaire d'importation avec filtrage dynamique.
     */
    

    /**
     * Téléchargement du template Excel multi-feuilles pour une classe et un trimestre donnés
     */
 public function downloadTemplate(Request $request)
{
    $validated = $request->validate([
        'annee_id' => 'required|exists:annees,id',
        'classe_id' => 'required|exists:classes,id',
        'trimestre_id' => 'required|exists:trimestres,id',
    ]);

    $annee_id = $validated['annee_id'];
    $classe_id = $validated['classe_id'];
    $trimestre_id = $validated['trimestre_id'];

    $annee = Annee::with(['classes','trimestres'])->find($annee_id);
    $classe = Classe::with('annees')->find($classe_id);
    $trimestre = Trimestre::find($trimestre_id);

    // Vérifier que la classe appartient bien à l'année
    if (!$annee->classes->contains($classe)) {
        return back()->with('error', 'Cette classe n’est pas attachée à l’année sélectionnée.');
    }

    // Vérifier que le trimestre appartient bien à l'année
    if (!$annee->trimestres->contains($trimestre)) {
        return back()->with('error', 'Le trimestre sélectionné n’est pas attaché à l’année.');
    }

    // Vérifier qu’il y a au moins une inscription pour cette année et cette classe
    $nombreInscriptions = \App\Models\Inscription::orderBy('eleve_id')->get()
        ->where('annee_id', $annee_id)
        ->where('classe_id', $classe_id)
        ->count();

    if ($nombreInscriptions === 0) {
        return back()->with('error', "La classe « {$classe->nom} » ne contient aucun élève inscrit pour l'année {$annee->nom}.");
    }

    $filename = 'Notes_' 
        . Str::slug($classe->nom) . '_' 
        . Str::slug($trimestre->nom) . '_' 
        . Str::slug($annee->nom) . '.xlsx';

    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\NotesTemplateMultiFeuillesExport($classe_id, $trimestre_id, $annee_id),
        $filename
    );
}


    /**
     * Prévisualiser les notes depuis un fichier Excel avant insertion
     */
    /**
 * Prévisualiser les notes depuis un fichier Excel avant insertion
 */
public function previsualiser(Request $request)
{
    $validated = $request->validate([
        'annee_id' => 'required|exists:annees,id',
        'classe_id' => 'required|exists:classes,id',
        'trimestre_id' => 'required|exists:trimestres,id',
        'matiere_id' => 'required|exists:matieres,id',
        'fichier' => 'required|file|mimes:xlsx,csv',
    ]);

    $annee = Annee::with(['classes','trimestres'])->find($validated['annee_id']);
    $classe = Classe::with('matieres')->find($validated['classe_id']);
    $trimestre = Trimestre::find($validated['trimestre_id']);
    $matiere = Matiere::find($validated['matiere_id']);

    // Vérifier cohérence
    if (!$annee->classes->contains($classe)) {
        return back()->with('error', 'La classe n’est pas attachée à l’année sélectionnée.');
    }
    if (!$annee->trimestres->contains($trimestre)) {
        return back()->with('error', 'Le trimestre n’est pas attaché à l’année.');
    }
    if (!$classe->matieres->contains($matiere)) {
        return back()->with('error', 'La matière n’est pas attachée à la classe.');
    }

    // Charger le fichier Excel
    $spreadsheet = IOFactory::load($request->file('fichier')->getPathname());
    $sheet = $spreadsheet->getSheetByName($matiere->nom);
    if (!$sheet) {
        return back()->with('error', "La feuille Excel pour la matière « {$matiere->nom} » est introuvable.");
    }

    $donnees = $sheet->toArray();
    $valides = [];
    $erreurs = [];

    foreach ($donnees as $index => $ligne) {
        if ($index === 0) continue; // entête

        // Extraction et nettoyage des données
        [$matricule, $nom, $prenom, $raw_interro, $raw_devoir1, $raw_devoir2] = array_map('trim', $ligne);

        // ✅ VALIDATION 1 : Matricule obligatoire
        if (empty($matricule)) {
            $erreurs[] = ['ligne' => compact('matricule','nom','prenom'), 'message' => 'Matricule manquant'];
            continue;
        }

        // ✅ CONVERSION FLEXIBLE : vide → null
        $moyenne_interro = !empty($raw_interro) ? floatval($raw_interro) : null;
        $devoir1 = !empty($raw_devoir1) ? floatval($raw_devoir1) : null;
        $devoir2 = !empty($raw_devoir2) ? floatval($raw_devoir2) : null;

        // ✅ VALIDATION 2 : Au moins UNE note
        $notes = array_filter([$moyenne_interro, $devoir1, $devoir2]);
        if (empty($notes)) {
            $erreurs[] = ['ligne' => compact('matricule','nom','prenom'), 'message' => 'Aucune note saisie (interro/devoir1/devoir2)'];
            continue;
        }

        // ✅ VALIDATION 3 : Notes entre 0-20 (SEULEMENT celles présentes)
        foreach($notes as $note){
            if($note < 0 || $note > 20){
                $erreurs[] = ['ligne' => compact('matricule','nom','prenom'), 'message' => 'Note hors limites (0-20)'];
                continue 2;
            }
        }

        // ✅ RECHERCHE INSCRIPTION
        $inscription = Inscription::with('eleve')
            ->whereHas('eleve', fn($q)=>$q->where('matricule',$matricule))
            ->where('annee_id',$validated['annee_id'])
            ->where('classe_id',$validated['classe_id'])
            ->first();

        if (!$inscription) {
            $erreurs[] = ['ligne' => compact('matricule','nom','prenom'), 'message' => "Aucune inscription trouvée pour {$matricule}"];
            continue;
        }

        // ✅ VÉRIFICATION DOUBLON
        $existe = Note::where([
            'inscription_id'=>$inscription->id,
            'matiere_id'=>$validated['matiere_id'],
            'trimestre_id'=>$validated['trimestre_id']
        ])->exists();

        if ($existe) {
            $erreurs[] = ['ligne'=>compact('matricule','nom','prenom'),'message'=>'Note déjà importée'];
            continue;
        }

        // ✅ AJOUT À LA LISTE VALIDE ✅
        $valides[] = [
            'inscription_id' => $inscription->id,
            'matricule' => $matricule,
            'nom' => $inscription->eleve->nom,
            'prenom' => $inscription->eleve->prenom,
            'moyenne_interro' => $moyenne_interro,  // Peut être null !
            'devoir1' => $devoir1,                  // Peut être null !
            'devoir2' => $devoir2,                  // Peut être null !
        ];
    }

    return view('notes.previsualisation', [
        'valides'        => $valides,
        'erreurs'        => $erreurs,
        // IDs (pour formulaires cachés)
        'annee_id'       => $validated['annee_id'],
        'classe_id'      => $validated['classe_id'],
        'trimestre_id'   => $validated['trimestre_id'],
        'matiere_id'     => $validated['matiere_id'],
        // 🔥 OBJETS COMPLETS (POUR LE BLADE)
        'annee'          => $annee,
        'classe'         => $classe,
        'trimestre'      => $trimestre,
        'matiere'        => $matiere,
        'fichier_nom'    => strtolower($request->file('fichier')->getClientOriginalName()),
    ]);
}


    /**
     * Insérer les notes validées
     */
    public function inserer(Request $request)
{
    $valides = json_decode($request->input('valides'), true);
    if (empty($valides)) return back()->with('error','Aucune donnée valide à insérer.');

    $annee_id = $request->annee_id;
    $trimestre_id = $request->trimestre_id;
    $matiere_id = $request->matiere_id;

    $nbInsertions = 0;
    $nbMisesAJour = 0;
    $nbErreurs = 0;

    foreach($valides as $ligne){
        try{
            if(!isset($ligne['inscription_id'])){
                $nbErreurs++; continue;
            }

            $inscription = Inscription::find($ligne['inscription_id']);
            if(!$inscription){ $nbErreurs++; continue; }

            // Récupération des notes disponibles (peuvent être vides/nulles)
            $moyenne_interro = isset($ligne['moyenne_interro']) ? floatval($ligne['moyenne_interro']) : null;
            $devoir1 = isset($ligne['devoir1']) ? floatval($ligne['devoir1']) : null;
            $devoir2 = isset($ligne['devoir2']) ? floatval($ligne['devoir2']) : null;

            // Validation : notes présentes doivent être entre 0 et 20
            $notes = array_filter([$moyenne_interro, $devoir1, $devoir2]);
            foreach($notes as $note){
                if($note<0 || $note>20){
                    $nbErreurs++; continue 2; // Skip cette ligne entière
                }
            }

            // 🔹 CALCUL INTELLIGENT DE LA MOYENNE (selon notes disponibles)
            $moyenne_matiere = $this->calculerMoyenneFlexible($moyenne_interro, $devoir1, $devoir2);
            
            if($moyenne_matiere === null){
                $nbErreurs++; continue; // Aucune note valide
            }

            $appreciation = $this->getAppreciation($moyenne_matiere);

            $noteExistante = Note::where('inscription_id',$inscription->id)
                ->where('matiere_id',$matiere_id)
                ->where('trimestre_id',$trimestre_id)
                ->first();

            if($noteExistante){
                $noteExistante->update([
                    'classe_id'=>$inscription->classe_id,
                    'matiere_id'=>$matiere_id,
                    'trimestre_id'=>$trimestre_id,
                    'annee_id'=>$annee_id,
                    'moyenne_interro'=>$moyenne_interro,
                    'devoir1'=>$devoir1,
                    'devoir2'=>$devoir2,
                    'moyenne_matiere'=>$moyenne_matiere,
                    'appreciation'=>$appreciation,
                ]);
                $nbMisesAJour++;
            }else{
                Note::create([
                    'inscription_id'=>$inscription->id,
                    'classe_id'=>$inscription->classe_id,
                    'matiere_id'=>$matiere_id,
                    'trimestre_id'=>$trimestre_id,
                    'annee_id'=>$annee_id,
                    'moyenne_interro'=>$moyenne_interro,
                    'devoir1'=>$devoir1,
                    'devoir2'=>$devoir2,
                    'moyenne_matiere'=>$moyenne_matiere,
                    'appreciation'=>$appreciation,
                ]);
                $nbInsertions++;
            }
        }catch(\Exception $e){
            $nbErreurs++;
            Log::error("Erreur insertion note : ".$e->getMessage());
        }
    }

    $message = "Importation terminée. ";
    if($nbInsertions>0) $message.=" $nbInsertions notes insérées.";
    if($nbMisesAJour>0) $message.=" $nbMisesAJour notes mises à jour.";
    if($nbErreurs>0) $message.=" ⚠️ $nbErreurs erreurs détectées.";

    return redirect()->route('notes.import')->with('success',$message);
}

/**
 * Calcule la moyenne selon les notes disponibles
 */
private function calculerMoyenneFlexible($moyenne_interro, $devoir1, $devoir2)
{
    $notes = array_filter([$moyenne_interro, $devoir1, $devoir2]); // Exclut les null/vides
    
    if(empty($notes)) return null; // Aucune note
    
    return round(array_sum($notes) / count($notes), 2);
}

    private function getAppreciation(float $moyenne): string
    {
        if($moyenne<2) return 'Nul';
        elseif($moyenne<4) return 'Médiocre';
         elseif($moyenne<6) return 'Très Faible';
          elseif($moyenne<8) return 'Faible';
        elseif($moyenne<10) return 'Insuffisant';
        elseif($moyenne<12) return 'Passable';
        elseif($moyenne<14) return 'Assez Bien';
        elseif($moyenne<16) return 'Bien';
        elseif($moyenne<18) return 'Très Bien';
        elseif($moyenne<=20) return 'Excellent';
        return 'Note invalide';
    }

    // Les autres méthodes CRUD restent similaires mais peuvent être adaptées pour filtrer par année/classe/matière si nécessaire
}
