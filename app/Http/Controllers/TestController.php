<?php

// app/Http/Controllers/TestController.php
namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Trimestre;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Afficher la liste des tests
     */
 public function index(Request $request)
{
    $annees = Annee::orderBy('nom')->get();

    // classes filtrées par année
    $classes = Classe::when($request->filled('annee_id'), function ($q) use ($request) {
            $q->whereHas('annees', fn ($a) =>
                $a->where('annee_id', $request->annee_id)
            );
        })
        ->orderBy('nom')
        ->get();

    // matières filtrées par classe
    $matieres = Matiere::when($request->filled('classe_id'), function ($q) use ($request) {
            $q->whereHas('classes', fn ($c) =>
                $c->where('classe_id', $request->classe_id)
            );
        })
        ->orderBy('nom')
        ->get();

    $tests = Test::with(['annee', 'classe', 'matiere', 'trimestre'])
        ->when($request->filled('annee_id'),
            fn ($q) => $q->where('annee_id', $request->annee_id)
        )
        ->when($request->filled('classe_id'),
            fn ($q) => $q->where('classe_id', $request->classe_id)
        )
        ->when($request->filled('matiere_id'),
            fn ($q) => $q->where('matiere_id', $request->matiere_id)
        )
        ->when($request->filled('search'), function ($q) use ($request) {
            $s = $request->search;
            $q->where(function ($qq) use ($s) {
                $qq->where('titre', 'ILIKE', "%$s%")
                   ->orWhereHas('matiere', fn ($m) => $m->where('nom', 'ILIKE', "%$s%"))
                   ->orWhereHas('classe', fn ($c) => $c->where('nom', 'ILIKE', "%$s%"));
            });
        })
        ->orderByDesc('date')
        ->paginate(15000)
        ->withQueryString();

    return view('tests.index', compact(
        'tests',
        'annees',
        'classes',
        'matieres'
    ));
}

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
         $trimestres = Trimestre::orderBy('id', 'asc')->get();
        return view('tests.create', compact('trimestres'));
    }

    /**
     * Stocker un nouveau test
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'matiere_id' => 'nullable|integer',
            'trimestre_id' => 'nullable|integer',
            'type' =>  'required|in:interro,devoir,composition,examen',
            'classe_id' => 'nullable|integer',
            'annee_id' => 'nullable|integer',
            'description' => 'nullable|string',
             'date' => 'required|date',
            'fichier' => 'required|file|mimes:pdf,doc,docx|max:10240', // max 10 Mo
        ]);

        if ($request->hasFile('fichier')) {
    // Stocke dans storage/app/public/tests pour que le lien asset() fonctionne
    $validated['fichier'] = $request->file('fichier')->store('tests', 'public');
}
        Test::create($validated);

        return redirect()->route('tests.index')->with('success', 'Test ajouté avec succès !');
    }

    /**
     * Afficher un test spécifique
     */
    public function show(Test $test)
    {
        return view('tests.show', compact('test'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Test $test)
    {
         $trimestres = Trimestre::orderBy('id', 'asc')->get();
        return view('tests.edit', compact('test'));
    }

    /**
     * Mettre à jour un test
     */
    public function update(Request $request, Test $test)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'matiere_id' => 'nullable|integer',
            'trimestre_id' => 'nullable|integer',
            'type' =>  'required|in:interro,devoir,composition,examen',
            'classe_id' => 'nullable|integer',
            'annee_id' => 'nullable|integer',
            'description' => 'nullable|string',
             'date' => 'required|date',
            'fichier' => 'required|file|mimes:pdf,doc,docx|max:10240', // max 10 Mo
        ]);

        if ($request->hasFile('fichier')) {
            $validated['fichier'] = $request->file('fichier')->store('tests');
        }

        $test->update($validated);

        return redirect()->route('tests.index')->with('success', 'Test mis à jour avec succès !');
    }

    /**
     * Supprimer un test
     */
    public function destroy(Test $test)
    {
        // Supprimer le fichier si existant
        if ($test->fichier) {
            \Storage::delete($test->fichier);
        }

        $test->delete();

        return redirect()->route('tests.index')->with('success', 'Test supprimé !');
    }

    ////MULTICREATION//////////////////////////////////////

    // map matière -> abréviation attendue dans le nom du fichier
    private $matiereMap = [
        'espagnol' => 'espa',
        'mathématique' => 'math',
        'mathématiques' => 'math',
        'anglais' => 'ang',
        'pct' => 'pct',
        'physique-chimie-technologie' => 'pct',
        'svt' => 'svt',
        'histoire-géographie' => 'hg',
        'philosophie' => 'philo',
        'lecture' => 'lec',
        'communication écrite' => 'com',
        'communication_ecrite' => 'com',
        'français' => 'fran',
        'francais' => 'fran',
    ];

    // map classe -> abréviation attendue
    private $classeMap = [
        '6ème' => '6eme', '6eme' => '6eme',
        '5ème' => '5eme', '5eme' => '5eme',
        '4ème' => '4eme', '4eme' => '4eme',
        '3ème' => '3eme', '3eme' => '3eme',
        '2nde' => '2nde',
        '1ère' => '1ere', '1ere' => '1ere',
        'terminale' => 'tle', 'tale' => 'tle', 'tl' => 'tle'
    ];

    public function createMultiple()
    {
        return view('tests.create-multiple', [
            'annees' => \App\Models\Annee::orderBy('nom', 'desc')->get(),
            'classes' => Classe::all(),
            'trimestres' => \App\Models\Trimestre::all(),
            'types' => ['interro','devoir','composition','examen']
        ]);
    }

    public function storeMultiple(Request $request)
    {
        // règles basiques
        $request->validate([
            'tests' => 'required|array|min:1',
            'tests.*.titre' => 'required|string|max:255',
            'tests.*.date' => 'required|date',
            'tests.*.type' => 'required',
            'tests.*.annee_id' => 'required|exists:annees,id',
            'tests.*.classe_id' => 'required|exists:classes,id',
            'tests.*.matiere_id' => 'required|exists:matieres,id',
            'tests.*.trimestre_id' => 'required|exists:trimestres,id',
            'tests.*.fichier' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        foreach ($request->tests as $idx => $t) {
            $classe  = Classe::find($t['classe_id']);
            $matiere = Matiere::find($t['matiere_id']);

            // si fichier fourni, validation avancée
            if (isset($t['fichier'])) {
                $file = $t['fichier'];
                $orig = $file->getClientOriginalName();

                if (!$this->fichierCorrespondMatiereEtClasse($orig, $matiere->nom, $classe->nom)) {
                    return redirect()->back()
                        ->withErrors(["tests.$idx.fichier" => "Le fichier '{$orig}' ne correspond pas à la classe '{$classe->nom}' et/ou à la matière '{$matiere->nom}' (respecte la nomenclature)."])
                        ->withInput();
                }

                // stockage
                $path = $file->store('tests', 'public');
            } else {
                $path = null;
            }

            Test::create([
                'titre' => $t['titre'],
                'date' => $t['date'],
                'type' => $t['type'],
                'annee_id' => $t['annee_id'],
                'classe_id' => $t['classe_id'],
                'matiere_id' => $t['matiere_id'],
                'trimestre_id' => $t['trimestre_id'],
                'description' => $t['description'] ?? null,
                'fichier' => $path,
            ]);
        }

        return redirect()->route('tests.index')->with('success', 'Tests enregistrés avec succès.');
    }

    // Normaliser une chaîne (sans accents, minuscules, underscores)
    private function normaliser($str)
    {
        $s = Str::lower($str);
        $s = Str::ascii($s); // retire les accents (Laravel 9+)
        $s = preg_replace('/[^a-z0-9]/', '_', $s);
        return $s;
    }

    // Vérifie si le nom du fichier contient l'abréviation matière et classe attendues
    private function fichierCorrespondMatiereEtClasse($filename, $matiereNom, $classeNom)
    {
        $fn = $this->normaliser($filename); // ex : '4eme_math_devoir1_pdf'

        // matière -> abbr
        $matiereKey = $this->normaliser($matiereNom);
        $abbrMatiere = $this->matiereMap[$matiereKey] ?? null;

        // classe -> abbr
        $classeKey = $this->normaliser($classeNom);
        $abbrClasse = $this->classeMap[$classeKey] ?? null;

        // si on a au moins abbrMatiere et abbrClasse -> vérifier qu'ils sont contenus
        if ($abbrMatiere && $abbrClasse) {
            return (str_contains($fn, $abbrMatiere) && str_contains($fn, $abbrClasse));
        }

        // fallback : si aucune abbr définie, vérifier présence mots courts (matiere/classe)
        if ($abbrMatiere && !str_contains($fn, $abbrMatiere)) return false;
        if ($abbrClasse && !str_contains($fn, $abbrClasse)) return false;

        return true;
    }
}
