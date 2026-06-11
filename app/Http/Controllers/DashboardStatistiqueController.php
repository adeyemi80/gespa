<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Moyenne;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardStatistiqueController extends Controller
{
    public function index(Request $request)
    {
        $annee_id = $request->annee_id ?? null;
        $trimestre_id = $request->trimestre_id ?? null;
        $classe_id = $request->classe_id ?? null;

        // 🔥 LISTES POUR FILTRES
        $annees = Annee::orderBy('id', 'desc')->get();
        $trimestres = Trimestre::all();
        $classes = Classe::all();

        // ❗ année obligatoire
        if (!$annee_id) {
            return view('dashboard_statistique.index', [
                'stats' => null,
                'topEleves' => collect(),
                'parClasse' => collect(),
                'annee_id' => null,
                'trimestre_id' => null,
                'classe_id' => null,
                'annees' => $annees,
                'trimestres' => $trimestres,
                'classes' => $classes,
            ]);
        }

        // 🔥 BASE INSCRIPTIONS
        $inscriptions = Inscription::with([
                'eleve',
                'classe',
                'moyennes' => function ($q) use ($annee_id, $trimestre_id) {
                    $q->where('annee_id', $annee_id);

                    if ($trimestre_id) {
                        $q->where('trimestre_id', $trimestre_id);
                    }
                }
            ])
            ->where('annee_id', $annee_id)
            ->when($classe_id, function ($q) use ($classe_id) {
                $q->where('classe_id', $classe_id);
            })
            ->get();

        // 📊 STATS
        $stats = $this->calculerStats($inscriptions);

        // 🏆 CLASSEMENT COMPLET (TOUS LES ÉLÈVES)
        $topEleves = $inscriptions
            ->map(function ($i) {
                $i->moyenne = optional($i->moyennes->first())->moyenne_trimestrielle ?? 0;
                return $i;
            })
            ->sortByDesc('moyenne')
            ->values(); // ✅ PAS DE LIMIT

        // 🏫 MOYENNE PAR CLASSE
        $parClasse = $inscriptions
            ->groupBy('classe_id')
            ->map(function ($group) {
                return [
                    'classe' => $group->first()->classe,
                    'moyenne' => round($group->avg(function ($i) {
                        return optional($i->moyennes->first())->moyenne_trimestrielle ?? 0;
                    }), 2)
                ];
            })
            ->values();

        return view('dashboard_statistique.index', compact(
            'stats',
            'topEleves',
            'parClasse',
            'annee_id',
            'trimestre_id',
            'classe_id',
            'annees',
            'trimestres',
            'classes'
        ));
    }

    // 📊 STATISTIQUES
    private function calculerStats($inscriptions)
    {
        $notes = $inscriptions->map(function ($i) {
            return optional($i->moyennes->first())->moyenne_trimestrielle ?? 0;
        });

        $effectif = $inscriptions->count();

        if ($effectif === 0) {
            return [
                'effectif' => 0,
                'moyenne_generale' => 0,
                'meilleure_moyenne' => 0,
                'plus_faible_moyenne' => 0,
                'taux_reussite' => 0,
                'admis' => 0,
                'echoues' => 0,
            ];
        }

        $admis = $notes->filter(fn($n) => $n >= 10)->count();

        return [
            'effectif' => $effectif,
            'moyenne_generale' => round($notes->avg(), 2),
            'meilleure_moyenne' => $notes->max(),
            'plus_faible_moyenne' => $notes->min(),
            'taux_reussite' => round(($admis / $effectif) * 100, 2),
            'admis' => $admis,
            'echoues' => $effectif - $admis,
        ];
    }

    public function exportPdf(Request $request)
{
    $annee_id = $request->annee_id;
    $trimestre_id = $request->trimestre_id;
    $classe_id = $request->classe_id;
    $trimestre = Trimestre::find($trimestre_id);

    if (!$annee_id) {
        return redirect()->back()->with('error', 'Veuillez sélectionner une année.');
    }

    // 🔥 INSCRIPTIONS
    $inscriptions = Inscription::with([
            'eleve',
            'classe',
            'moyennes' => function ($q) use ($annee_id, $trimestre_id) {
                $q->where('annee_id', $annee_id);

                if ($trimestre_id) {
                    $q->where('trimestre_id', $trimestre_id);
                }
            }
        ])
        ->where('annee_id', $annee_id)
        ->when($classe_id, function ($q) use ($classe_id) {
            $q->where('classe_id', $classe_id);
        })
        ->get();

    // 🏆 CLASSEMENT
    $topEleves = $inscriptions
        ->map(function ($i) {
            $i->moyenne = optional($i->moyennes->first())->moyenne_trimestrielle ?? 0;
            return $i;
        })
        ->sortByDesc('moyenne')
        ->values();

    $pdf = Pdf::loadView('dashboard_statistique.pdf', [
        'topEleves' => $topEleves,
        'annee_id' => $annee_id,
        'trimestre_id' => $trimestre_id,
        'classe_id' => $classe_id,
        'trimestre' => $trimestre,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('classement-statistique.pdf');
}

public function classementParClasse(Request $request)
{
    $annee_id = $request->annee_id;
    $trimestre_id = $request->trimestre_id;

    $annees = Annee::orderBy('id', 'desc')->get();
    $trimestres = Trimestre::all();

    if (!$annee_id) {
        return view('dashboard_statistique.classement_par_classe', [
            'classes' => collect(),
            'annees' => $annees,
            'trimestres' => $trimestres,
            'annee_id' => null,
            'trimestre_id' => null,
        ]);
    }

    $inscriptions = Inscription::with([
            'eleve',
            'classe',
            'moyennes' => function ($q) use ($annee_id, $trimestre_id) {
                $q->where('annee_id', $annee_id);

                if ($trimestre_id) {
                    $q->where('trimestre_id', $trimestre_id);
                }
            }
        ])
        ->where('annee_id', $annee_id)
        ->get()
        ->map(function ($i) {
            $i->moyenne = optional($i->moyennes->first())->moyenne_trimestrielle ?? 0;
            return $i;
        })
        ->groupBy('classe_id')
        ->map(function ($group) {
            return [
                'classe' => $group->first()->classe,
                'eleves' => $group->sortByDesc('moyenne')->values()
            ];
        });

    return view('dashboard_statistique.classement_par_classe', compact(
        'inscriptions',
        'annees',
        'trimestres',
        'annee_id',
        'trimestre_id'
    ))->with('classes', $inscriptions);
}

public function exportClassementParClassePdf(Request $request)
{
    $annee_id = $request->annee_id;
    $trimestre_id = $request->trimestre_id;
$trimestre = Trimestre::find($trimestre_id);
$annee = Annee::find($annee_id);
    if (!$annee_id) {
        return redirect()->back()->with('error', 'Veuillez sélectionner une année.');
    }

    $inscriptions = Inscription::with([
            'eleve',
            'classe',
            'moyennes' => function ($q) use ($annee_id, $trimestre_id) {
                $q->where('annee_id', $annee_id);

                if ($trimestre_id) {
                    $q->where('trimestre_id', $trimestre_id);
                }
            }
        ])
        ->where('annee_id', $annee_id)
        ->get()
        ->map(function ($i) {
            $i->moyenne = optional($i->moyennes->first())->moyenne_trimestrielle ?? 0;
            return $i;
        })
        ->groupBy('classe_id')
        ->map(function ($group) {
            return [
                'classe' => $group->first()->classe,
                'eleves' => $group->sortByDesc('moyenne')->values()
            ];
        });

    $pdf = Pdf::loadView('dashboard_statistique.pdf_classe_par_classe', [
        'classes' => $inscriptions,
        'annee_id' => $annee_id,
        'trimestre_id' => $trimestre_id,
        'trimestre' => $trimestre,
         'annee' => $annee,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('classement-par-classe.pdf');
}

public function classementAnnuel(Request $request)
{
    // 📅 Année sélectionnée OU année en cours
    $annee_id = $request->annee_id
        ?? Annee::where('en_cours', true)->first()?->id;

    $annee = Annee::find($annee_id);

    $annees = Annee::orderBy('debut', 'desc')->get();

    // 🏆 CLASSEMENT ANNÉE (TRIMESTRE 3 = FINAL)
    $topEleves = Moyenne::with([
            'inscription.eleve',
            'inscription.classe'
        ])
        ->where('annee_id', $annee_id)
        ->where('trimestre_id', 3)
        ->whereNotNull('moyenne_annuelle')
        ->orderByDesc('moyenne_annuelle')
        ->get()
        ->unique('inscription_id')
        ->values();

    // 📊 STATISTIQUES
    $effectif = $topEleves->count();

    $moyenne_generale = $effectif > 0
        ? $topEleves->avg('moyenne_annuelle')
        : 0;

    $admis = $topEleves->where('moyenne_annuelle', '>=', 10)->count();

    $echoues = $effectif - $admis;

    $stats = [
        'effectif' => $effectif,
        'moyenne_generale' => $moyenne_generale,
        'admis' => $admis,
        'echoues' => $echoues,
    ];

    // 🧠 PASSAGE + CLASSE SUIVANTE
    foreach ($topEleves as $moyenne) {

        $m = $moyenne->moyenne_annuelle ?? 0;

        $classeActuelle = $moyenne->inscription->classe ?? null;

        if ($m >= 10) {

            $classeSuivante = $classeActuelle
                ? Classe::where('ordre', $classeActuelle->ordre + 1)->first()
                : null;

            $moyenne->passage = $classeSuivante
                ? 'Passe en classe de ' . $classeSuivante->nom
                : 'Passe en classe de';

        } else {
            $moyenne->passage = 'Redouble';
        }
    }

    return view('dashboard_statistique.classement_annuel', compact(
        'topEleves',
        'stats',
        'annees',
        'annee',
        'annee_id'
    ));
}

public function classementAnnuelPdf(Request $request)
{
    $annee_id = $request->annee_id
        ?? Annee::where('en_cours', true)->first()?->id;

    $annee = Annee::find($annee_id);

    $topEleves = Moyenne::with([
            'inscription.eleve',
            'inscription.classe'
        ])
        ->where('annee_id', $annee_id)
        ->where('trimestre_id', 3)
        ->whereNotNull('moyenne_annuelle')
        ->orderByDesc('moyenne_annuelle')
        ->get()
        ->unique('inscription_id')
        ->values();

    $effectif = $topEleves->count();

    $moyenne_generale = $effectif > 0
        ? $topEleves->avg('moyenne_annuelle')
        : 0;

    $admis = $topEleves->where('moyenne_annuelle', '>=', 10)->count();

    $echoues = $effectif - $admis;

    $stats = compact('effectif', 'moyenne_generale', 'admis', 'echoues');

    foreach ($topEleves as $moyenne) {

        $m = $moyenne->moyenne_annuelle ?? 0;

        $classeActuelle = $moyenne->inscription->classe ?? null;

        if ($m >= 10) {

            $classeSuivante = $classeActuelle
                ? Classe::where('ordre', $classeActuelle->ordre + 1)->first()
                : null;

            $moyenne->passage = $classeSuivante
                ? 'Passe en ' . $classeSuivante->nom
                : 'Passe';

        } else {
            $moyenne->passage = 'Redouble';
        }
    }

    $pdf = Pdf::loadView('dashboard_statistique.pdf.classement_annuel', compact(
        'topEleves',
        'stats',
        'annee'
    ));

    return $pdf->download('classement_annuel_'.$annee->nom.'.pdf');
}


public function classementAnnuelParClasse(Request $request)
{
    // 📅 année en cours ou sélectionnée
    $anneeActuelle = Annee::where('en_cours', true)->first();

    $annee_id = $request->annee_id ?? $anneeActuelle?->id;
    $classe_id = $request->classe_id;

    $annee = Annee::find($annee_id);

    $annees = Annee::orderBy('debut', 'desc')->get();

    // 🧠 CLASSES cycle 3 uniquement
    $classesQuery = Classe::where('cycle_id', 3)->orderBy('ordre');

    if ($classe_id) {
        $classesQuery->where('id', $classe_id);
    }

    $classes = $classesQuery->get();

    $trimestre_id = 3;

    // 📦 moyennes trimestre 3 uniquement
    $moyennes = Moyenne::with(['inscription.eleve', 'inscription.classe'])
        ->where('annee_id', $annee_id)
        ->where('trimestre_id', $trimestre_id)
        ->whereNotNull('moyenne_annuelle')
        ->whereHas('inscription.classe', function ($q) {
            $q->where('cycle_id', 3);
        })
        ->orderByDesc('moyenne_annuelle')
        ->get();

    // 🏫 CONSTRUCTION classesData
    $classesData = $classes->map(function ($classe) use ($moyennes) {

        $eleves = $moyennes->filter(function ($m) use ($classe) {
            return optional($m->inscription->classe)->id === $classe->id;
        })->values();

        return [
            'classe' => $classe,
            'eleves' => $eleves,
        ];
    });

    // ❌ FILTRE IMPORTANT (classes vides supprimées)
    $classesData = $classesData->filter(function ($c) {
        return $c['eleves']->count() > 0;
    })->values();

    // 📊 STATS
    $effectif = $moyennes->count();

    $moyenne_generale = $effectif > 0
        ? $moyennes->avg('moyenne_annuelle')
        : 0;

    $admis = $moyennes->where('moyenne_annuelle', '>=', 10)->count();
    $echoues = $effectif - $admis;

    $stats = [
        'effectif' => $effectif,
        'moyenne_generale' => $moyenne_generale,
        'admis' => $admis,
        'echoues' => $echoues,
    ];

    return view('dashboard_statistique.classement_annuel_par_classe', compact(
        'annees',
        'annee',
        'annee_id',
        'classes',
        'classe_id',
        'classesData',
        'stats'
    ));
}

public function exportClassementAnnuelParClassePdf(Request $request)
{
    // 📅 année en cours
    $anneeActuelle = Annee::where('en_cours', true)->first();

    $annee_id = $request->annee_id ?? $anneeActuelle?->id;
    $classe_id = $request->classe_id;

    $annee = Annee::find($annee_id);

    // 🧠 cycle 3 uniquement
    $classesQuery = Classe::where('cycle_id', 3)
        ->orderBy('ordre');

    if ($classe_id) {
        $classesQuery->where('id', $classe_id);
    }

    $classes = $classesQuery->get();

    $trimestre_id = 3;

    // 📦 moyennes trimestre 3
    $moyennes = Moyenne::with(['inscription.eleve', 'inscription.classe'])
        ->where('annee_id', $annee_id)
        ->where('trimestre_id', $trimestre_id)
        ->whereNotNull('moyenne_annuelle')
        ->whereHas('inscription.classe', function ($q) {
            $q->where('cycle_id', 3);
        })
        ->when($classe_id, function ($q) use ($classe_id) {
            $q->whereHas('inscription', function ($sub) use ($classe_id) {
                $sub->where('classe_id', $classe_id);
            });
        })
        ->orderByDesc('moyenne_annuelle')
        ->get();

    // 🏫 groupement + suppression des classes vides
    $classesData = $classes->map(function ($classe) use ($moyennes) {

        $eleves = $moyennes->filter(function ($m) use ($classe) {
            return optional($m->inscription->classe)->id === $classe->id;
        })->values();

        return [
            'classe' => $classe,
            'eleves' => $eleves,
        ];
    })
    // ❌ on supprime les classes sans élèves
    ->filter(function ($data) {
        return $data['eleves']->count() > 0;
    })
    ->values();

    // 📄 génération PDF
    $pdf = Pdf::loadView('dashboard_statistique.pdf.classement_annuel_par_classe', [
        'classesData' => $classesData,
        'annee' => $annee
    ]);

    return $pdf->download('classement-annuel-par-classe.pdf');
}

}