<?php

namespace App\Http\Controllers;

use App\Models\Conduite;
use App\Models\Inscription;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Services\MoyenneService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ModeleConduiteExport;

class ConduiteController extends Controller
{
    protected MoyenneService $moyenneService;
    protected $notifier;
    public function __construct(MoyenneService $moyenneService, NotificationService $notifier)
    {
        $this->moyenneService = $moyenneService;
        $this->notifier = $notifier;
    }

    /* ===========================
     *  LISTE
     * =========================== */
    public function index()
    {
        $conduites = Conduite::with([
                'inscription.eleve',
                'inscription.classe',
                'trimestre'
            ])
            ->latest()
            ->paginate(10000);
$inscriptions = Inscription::all();
        return view('conduites.index', compact('conduites', 'inscriptions'));
    }

    /* ===========================
     *  FORMULAIRE MANUEL
     * =========================== */
    public function create()
    {
        return view('conduites.create', [
            'annees'      => Annee::orderBy('nom')->get(),
            'classes'     => collect(),   // AJAX
            'trimestres'  => collect(),   // AJAX
            'inscriptions'=> collect(),   // AJAX
        ]);
    }

    /* ===========================
     *  ENREGISTREMENT MANUEL
     * =========================== */
    public function store(Request $request)
    {
        $request->validate([
            'inscription_id' => 'required|exists:inscriptions,id',
            'trimestre_id'   => 'required|exists:trimestres,id',
            'note_conduite'  => 'required|numeric|min:0|max:20',
        ]);

        $inscription = Inscription::with('eleve')->findOrFail($request->inscription_id);

        Conduite::updateOrCreate(
            [
                'inscription_id' => $inscription->id,
                'trimestre_id'   => $request->trimestre_id,
            ],
            [
                'annee_id'      => $inscription->annee_id,
                'classe_id'     => $inscription->classe_id,
                'note_conduite' => $request->note_conduite,
                'matricule'     => $inscription->eleve->matricule,
            ]
        );

        return redirect()
            ->route('conduites.index')
            ->with('success', 'Note de conduite enregistrée avec succès.');
    }

    /* ===========================
     *  IMPORTATION
     * =========================== */
 public function import(Request $request)
{
    // Force cycle_id=3 (ignorant la requête si besoin)
    $cycleId = 3;  // Ou $request->cycle_id ?? 3 pour flexibilité

    // Validation numérique (évite erreurs PostgreSQL comme vos précédents bugs)
    if (!is_numeric($cycleId) || $cycleId != 3) {
        $cycleId = 3;
    }

    return view('conduites.import', [
        'annees'     => Annee::orderBy('nom')->get(),
        'classes'    => Classe::where('cycle_id', $cycleId)
                             ->orderBy('ordre')
                             ->get(),
        'trimestres' => collect(),
        'cycle_id'   => $cycleId,  // Pour debug en vue
    ]);
}
    /* ===========================
     *  TEMPLATE EXCEL
     * =========================== */
    public function template($classeId, $trimestreId, $anneeId)
    {
        $annee     = Annee::findOrFail($anneeId);
        $classe    = $annee->classesActives()->findOrFail($classeId);
        $trimestre = $annee->trimestresActifs()->findOrFail($trimestreId);

        $filename = 'modele_conduite_' .
            str_replace(' ', '_', strtolower($classe->nom)) . '_' .
            str_replace(' ', '_', strtolower($trimestre->nom)) . '_' .
            str_replace(' ', '_', strtolower($annee->nom)) . '.xlsx';

        return Excel::download(
    new ModeleConduiteExport($classeId, $anneeId, $trimestreId),
    $filename
);

    }

    /* ===========================
     *  PRÉVISUALISATION
     * =========================== */
    public function previsualiser(Request $request)
    {
        $request->validate([
            'annee_id'     => 'required|exists:annees,id',
            'classe_id'    => 'required|exists:classes,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'fichier'      => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $annee = Annee::findOrFail($request->annee_id);

        // 🔒 Sécurité métier ABSOLUE
        if (!$annee->classes()->where('classes.id', $request->classe_id)->exists()) {
            return back()->withErrors('Classe non rattachée à cette année.');
        }

        if (!$annee->trimestres()->where('trimestres.id', $request->trimestre_id)->exists()) {
            return back()->withErrors('Trimestre non rattaché à cette année.');
        }

        $rows = Excel::toArray([], $request->file('fichier'))[0];
        $rows = array_slice($rows, 1);

        $resultats = [];
        $valides = [];

        foreach ($rows as $i => $row) {
            [$matricule, $nom, $prenom, $note] = $row;
            $erreur = null;

            $inscription = Inscription::where('annee_id', $annee->id)
                ->where('classe_id', $request->classe_id)
                ->whereHas('eleve', fn($q) =>
                    $q->where('matricule', trim($matricule))
                )->first();

            if (!$inscription) {
                $erreur = 'Inscription introuvable';
            } elseif (!is_numeric($note)) {
                $erreur = 'Note invalide';
            }

            $ligne = [
                'ligne'         => $i + 2,
                'matricule'     => trim($matricule),
                'nom'           => $nom,
                'prenom'        => $prenom,
                'note_conduite' => $note,
                'inscription_id'=> $inscription?->id,
                'erreur'        => $erreur,
            ];

            $resultats[] = $ligne;
            if (!$erreur) $valides[] = $ligne;
        }

        return view('conduites.previsualisation', [
        'donnees' => $resultats,
        'valides' => $valides,
        'annee_id' => $request->annee_id,
        'classe_id' => $request->classe_id,
        'trimestre_id' => $request->trimestre_id,
    ] + $request->only('annee_id','classe_id','trimestre_id'));
    }

    /* ===========================
     *  INSERTION
     * =========================== */
    public function inserer(Request $request)
    {
        DB::transaction(function () use ($request) {
            foreach (json_decode($request->valides, true) as $ligne) {
                Conduite::updateOrCreate(
                    [
                        'inscription_id' => $ligne['inscription_id'],
                        'trimestre_id'   => $request->trimestre_id,
                    ],
                    [
                        'annee_id'      => $request->annee_id,
                        'classe_id'     => $request->classe_id,
                        'note_conduite' => round($ligne['note_conduite'], 2),
                        'matricule'     => $ligne['matricule'],
                    ]
                );
            }
        });

        return redirect()
            ->route('conduites.import')
            ->with('success', 'Importation des conduites terminée avec succès.');
    }

    /* ===========================
     *  AJAX – CLASSES PAR ANNÉE
     * =========================== */
    public function classesParAnnee($anneeId)
    {
        return Annee::findOrFail($anneeId)
            ->classesActives()
            ->orderBy('nom')
            ->get(['classes.id','classes.nom']);
    }

    /* ===========================
     *  AJAX – TRIMESTRES PAR ANNÉE
     * =========================== */
    public function trimestresParAnnee($anneeId)
    {
        return Annee::findOrFail($anneeId)
            ->trimestresActifs()
            ->orderBy('ordre')
            ->get(['trimestres.id','trimestres.nom']);
    }

    

    public function avertirParent($conduiteId)
    {
        $conduite = Conduite::findOrFail($conduiteId);
        $parent = $conduite->eleve->paren;

        $message = "Avertissement : " . $conduite->type .
                   " - " . $conduite->observation;

        // SMS
        if($parent->telephone) {
            $this->notifier->sendSMS($parent->telephone, $message);
        }

        // WhatsApp
        if($parent->whatsapp) {
            $this->notifier->sendWhatsApp($parent->whatsapp, $message);
        }

        return back()->with('success','Parent notifié avec succès.');
    }

    public function show($id)
{
    $conduite = Conduite::findOrFail($id);
    return view('conduites.show', compact('conduite'));
}
public function edit($id)
{
    $conduite = Conduite::findOrFail($id);
    $inscriptions = Inscription::all();
    $trimestres = Trimestre::all();
    return view('conduites.edit', compact('conduite', 'inscriptions', 'trimestres'));
}

public function update(Request $request, Conduite $conduite)
{
    $request->validate([
        'inscription_id' => 'required|exists:inscriptions,id',
        'trimestre_id'   => 'required|exists:trimestres,id',
        'note_conduite'  => 'required|numeric|min:0|max:20',
    ]);

    $conduite->update([
        'inscription_id' => $request->inscription_id,
        'trimestre_id'   => $request->trimestre_id,
        'note_conduite'  => $request->note_conduite,
    ]);

    return redirect()
        ->route('conduites.index')
        ->with('success', 'Conduite mise à jour avec succès');
}

}
