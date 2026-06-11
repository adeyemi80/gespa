<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TestFileParser;
use App\Models\Test;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Annee;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TestImportController extends Controller
{
    protected $parser;

    public function __construct(TestFileParser $parser)
    {
        $this->parser = $parser;
    }

    public function showImportForm()
    {
        $annees = Annee::with('trimestres')->orderBy('nom', 'desc')->get();
        $anneeActive = $annees->firstWhere('en_cours', true) ?? $annees->first();
        return view('tests.import', compact('annees', 'anneeActive'));
    }

    public function preview(Request $request)
{
    $request->validate([
        'annee_id' => 'required|exists:annees,id',
        'trimestre_id' => 'required|exists:trimestres,id',
        'date_test' => 'required|date',
        'titre' => 'required|string',
        'tests_files.*' => 'required|file|mimes:pdf,doc,docx,odt|max:51200',
    ]);

    $annee = Annee::findOrFail($request->annee_id);
    $trimestre = $annee->trimestres()->findOrFail($request->trimestre_id);

    $skipped = collect([]);
    $previews = collect([]);
    $sessionData = []; // ✅ Données pour l'import FINAL

    foreach ($request->file('tests_files') as $index => $file) {
        try {
            $parsed = $this->parser->parseFilename($file->getClientOriginalName());
            
            // Vérifications sécurisées
            $classes = $parsed['classes'] ?? [];
            $matiereName = $parsed['matiere_name'] ?? null;
            $type = $parsed['type'] ?? 'Non détecté';

            if (empty($classes) || empty($matiereName)) {
                $skipped->push($file->getClientOriginalName() . ' (Format invalide)');
                continue;
            }

            // Vérification matière
            $matiere = Matiere::where('nom', $matiereName)->first();
            if (!$matiere) {
                $skipped->push($file->getClientOriginalName() . ' (Matière inconnue: ' . $matiereName . ')');
                continue;
            }

            // Vérification classes pour l'année (sécurisée)
            $validClasses = Classe::whereIn('nom', $classes)
                ->whereHas('annees', fn($q) => $q->where('annee_id', $annee->id))
                ->get();

            if ($validClasses->isEmpty()) {
                $skipped->push($file->getClientOriginalName() . ' (Classe inexistante: ' . implode(', ', $classes) . ')');
                continue;
            }

            // ✅ SAUVEGARDER FICHIER TEMPORAIRE (CHE MIN, pas objet File)
            $tempPath = $file->store('temp_tests', 'local'); // local disk
            
            // Structure pour preview ET import (100% serialisable)
            $previewData = [
                'index' => $index,
                'filename' => $file->getClientOriginalName(),
                'temp_path' => $tempPath, // ✅ CHEMIN relatif
                'original_name' => $file->getClientOriginalName(),
                'type' => $type,
                'classes' => $validClasses->pluck('nom')->toArray(),
                'matiere' => $matiere->nom,
                'matiere_id' => $matiere->id,
                'classes_models' => $validClasses->pluck('id', 'nom')->toArray(), // id => nom
            ];

            $previews->push($previewData);
            $sessionData[$index] = $previewData; // ✅ 100% serialisable !

        } catch (\Exception $e) {
            $skipped->push($file->getClientOriginalName() . ' (Erreur parsing: ' . $e->getMessage() . ')');
        }
    }

    // ✅ SESSION COMPLÈTE (aucun objet non-serializable)
    session([
        'temp_import_data' => $sessionData,
        'import_form_data' => [
            'titre' => $request->titre,
            'date_test' => $request->date_test,
            'annee_id' => $request->annee_id,
            'trimestre_id' => $request->trimestre_id,
        ]
    ]);

    if ($skipped->isNotEmpty()) {
        session(['skipped_errors' => $skipped->toArray()]);
    }

    return view('tests.preview', [
        'previews' => $previews->toArray(), // ✅ TOUJOURS un tableau
        'skipped_errors' => $skipped->toArray(),
        'annee' => $annee,
        'trimestre' => $trimestre,
    ]);
}


   public function importFinal(Request $request)
{
    $request->validate([
        'kept_indexes' => 'required|array|min:1',
        'kept_indexes.*' => 'integer|min:0'
    ]);

    $keptIndexes = $request->kept_indexes;
    $sessionData = session('temp_import_data', []);
    $formData = session('import_form_data', []);

    if (empty($sessionData)) {
        return back()->with('error', 'Données expirées. Recommencez.');
    }

    $importedCount = 0;

    \DB::transaction(function () use ($keptIndexes, $sessionData, $formData, &$importedCount) {
        foreach ($keptIndexes as $index) {
            if (!isset($sessionData[$index])) continue;

            $data = $sessionData[$index];
            
            // ✅ SIMPLE : Copie directe du fichier temp vers public
            $tempPath = storage_path('app/' . $data['temp_path']);
            $finalPath = 'tests/' . $data['original_name'];
            
            // Copier vers storage/app/public/tests/
            Storage::disk('public')->put(
                $finalPath, 
                file_get_contents($tempPath)
            );
            
            // Créer test par classe (classes_models = [nom => id])
            foreach ($data['classes_models'] as $classeNom => $classeId) {
                Test::create([
                    'titre' => $formData['titre'],
                    'matiere_id' => $data['matiere_id'],
                    'classe_id' => $classeId,
                    'annee_id' => $formData['annee_id'],
                    'fichier' => $finalPath, // Chemin relatif public
                    'trimestre_id' => $formData['trimestre_id'],
                    'date' => $formData['date_test'],
                    'hash' => Str::random(12),
                    'type' => $data['type'],
                ]);
                $importedCount++;
            }
            
            // Supprimer temp
            Storage::disk('local')->delete($data['temp_path']);
        }
    });

    session()->forget(['temp_import_data', 'import_form_data', 'skipped_errors']);

    return redirect()->route('tests.importForm')
        ->with('success', "$importedCount test(s) importé(s) avec succès !");
}


    protected function normalizeClasses($classeDetected)
    {
        if (!$classeDetected) return [];

        $classeDetected = str_replace(
            ['1ere', '1ereCD', '2ndeCD', '3eme', '2nde'], 
            ['1ère', '1èreC+1èreD', '2ndeC+2ndeD', '3ème', '2nde'], 
            $classeDetected
        );

        return explode('+', $classeDetected);
    }
}
