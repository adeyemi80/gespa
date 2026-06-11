<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Matiere;
use App\Exports\MatieresModeleExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class MatiereImportController extends Controller
{
    public function index()
    {
        $annees = Annee::all();
        $classes = Classe::all();
         $cycles = Cycle::all();
        return view('matieres.import', compact('annees', 'classes', 'cycles'));
    }

public function telechargerModele(Request $request)
{
    $request->validate([
        'annee_id' => 'required|exists:annees,id',
        'cycle_id' => 'required|exists:cycles,id',
    ]);

    $anneeId = $request->annee_id;
    $cycleId = $request->cycle_id;

    $annee = Annee::with('classes')->findOrFail($anneeId);
    $cycle = Cycle::findOrFail($cycleId);

    // 🔥 filtrer les classes du cycle
    $classes = $annee->classes
        ->where('cycle_id', $cycleId)
        ->sortBy('ordre')
        ->values();

    // 🔥 nettoyer noms
    $anneeNom = str_replace(' ', '_', strtolower($annee->nom));
    $cycleNom = str_replace(' ', '_', strtolower($cycle->nom));

    return Excel::download(
        new MatieresModeleExport($classes, $cycle),
        "import_matieres_{$cycleNom}_{$anneeNom}.xlsx"
    );
}

    public function preview(Request $request)
    {
        $request->validate([
            'annee_id'      => 'required|exists:annees,id',
            'classe_id'     => 'required|exists:classes,id',
            'fichier_excel' => 'required|file|mimes:xlsx,xls'
        ]);

        $classe = Classe::findOrFail($request->classe_id);

        // Bloquer si la classe a déjà des matières
        $existingMatieres = Matiere::where('niveau', $classe->nom)->pluck('nom');
        if ($existingMatieres->isNotEmpty()) {
            $liste = $existingMatieres->implode(', ');
            return redirect()->route('matieres.import')
                ->with('error', "La classe {$classe->nom} possède déjà les matières : {$liste}");
        }

        $spreadsheet = IOFactory::load($request->file('fichier_excel'));
        $rows = [];

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            if (strtolower($worksheet->getTitle()) !== strtolower($classe->nom)) continue;

            foreach ($worksheet->toArray() as $i => $row) {
                if ($i === 0) continue;              // header
                if (empty(array_filter($row))) continue; // ligne vide
                $rows[] = $row;
            }
        }

        if (empty($rows)) {
            return redirect()->route('matieres.import')
                ->with('error', "Aucune donnée trouvée pour la classe {$classe->nom}");
        }

        return view('matieres.preview', [
            'rows'      => $rows,
            'header'    => ['Nom', 'Type', 'Coefficient', 'Niveau'],
            'classe'    => $classe,
            'classe_id' => $request->classe_id,
            'annee_id'  => $request->annee_id
        ]);
    }

    public function inserer(Request $request)
{
    $rows = $request->input('rows', []);
    $classe_id = $request->input('classe_id');

    if (empty($rows)) {
        return redirect()->route('matieres.import')
            ->with('error', 'Aucune donnée à importer.');
    }

    $classe = Classe::findOrFail($classe_id);

    $importees = 0;
    $dejaExistantes = [];
    $matiereIds = [];
    $nouvellesMatieres = [];

    foreach ($rows as $row) {

        $nom = trim($row[0] ?? '');
        $type = trim($row[1] ?? '');
        $coefficient = $row[2] ?? null;
        $niveau = trim($row[3] ?? '');

        // 🔒 Validation stricte
        if (!$nom || !$type || !$niveau || !is_numeric($coefficient)) {
            continue;
        }

        // 🔥 FILTRE CRITIQUE : empêcher mélange de niveaux
        if ($niveau !== $classe->niveau) {
            continue;
        }

        // 🔍 Recherche exacte (nom + niveau + type)
        $matiere = Matiere::where('nom', $nom)
            ->where('niveau', $niveau)
            ->where('type', $type)
            ->first();

        if ($matiere) {
            $matiereIds[] = $matiere->id;
            $dejaExistantes[] = $nom;
        } else {
            $nouvellesMatieres[] = [
                'nom' => $nom,
                'type' => $type,
                'coefficient' => (float)$coefficient,
                'niveau' => $niveau,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
    }

    DB::transaction(function () use (
        &$matiereIds,
        &$importees,
        $nouvellesMatieres,
        $classe
    ) {

        // ✅ 1. Insertion des nouvelles matières
        if (!empty($nouvellesMatieres)) {

            foreach ($nouvellesMatieres as $data) {
                $matiere = Matiere::create($data);
                $matiereIds[] = $matiere->id;
                $importees++;
            }
        }

        // ✅ 2. Sécurisation : récupérer uniquement les matières du BON niveau
        $matieresValides = Matiere::whereIn('id', $matiereIds)
            ->where('niveau', $classe->niveau)
            ->pluck('id')
            ->toArray();

        // ✅ 3. Vérifier les relations existantes
        $existingPivot = DB::table('classe_matiere')
            ->where('classe_id', $classe->id)
            ->whereIn('matiere_id', $matieresValides)
            ->pluck('matiere_id')
            ->toArray();

        // ✅ 4. Préparer insertion pivot
        $pivotData = [];

        foreach ($matieresValides as $id) {

            if (!in_array($id, $existingPivot)) {

                $pivotData[] = [
                    'classe_id' => $classe->id,
                    'matiere_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // ✅ 5. Insertion finale
        if (!empty($pivotData)) {
            DB::table('classe_matiere')->insert($pivotData);
        }
    });

    return redirect()->route('matieres.import')->with([
        'success' => "$importees matières importées pour cette classe.",
        'dejaExistantes' => array_unique($dejaExistantes)
    ]);
}

    
}