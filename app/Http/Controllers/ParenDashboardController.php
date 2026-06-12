<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Models\MessageParent;
use App\Models\NotificationParent;
use App\Models\Bulletin;
use App\Models\Conduite;
use App\Models\Note;
use App\Models\Moyenne;
use App\Models\Annee;

class ParenDashboardController extends Controller
{
    protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Parent lié à l'utilisateur connecté
        $paren = $user->parens()->first();

        // Aucun parent trouvé
        if (!$paren) {
            return view('parens.dashboard', [
                'inscriptions'       => collect(),
                'messages'           => collect(),
                'notificationsCount' => 0,
                'moyennes'           => collect(),
                'conduites'          => collect(),
                'notes'              => collect(),
                'bulletins'          => collect(),
                'annees'             => collect(),
                'annee'              => null,
                'trimestre'          => 1,
                'eleve_id'           => null,
                'paren'              => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ANNÉE SCOLAIRE SÉLECTIONNÉE
        |--------------------------------------------------------------------------
        */
        $annees = Annee::orderByDesc('id')->get();

        $annee_id = $request->get('annee_id', $annees->first()?->id);
        $annee    = Annee::find($annee_id);

        /*
        |--------------------------------------------------------------------------
        | TRIMESTRE SÉLECTIONNÉ
        |--------------------------------------------------------------------------
        */
        $trimestre = $request->get('trimestre_id', 1);

        /*
        |--------------------------------------------------------------------------
        | INSCRIPTIONS DES ENFANTS (filtrées par année)
        |--------------------------------------------------------------------------
        */
       
$inscriptions = $paren->inscriptions()
    ->with(['eleve', 'classe'])
    ->where('inscriptions.annee_id', $annee_id)
    ->get();

        /*
        |--------------------------------------------------------------------------
        | ÉLÈVE SÉLECTIONNÉ (optionnel, sinon premier enfant)
        |--------------------------------------------------------------------------
        */
        $eleve_id = $request->get('eleve_id', $inscriptions->first()?->eleve_id);

        // Inscription correspondant à l'élève + année sélectionnés
        $inscriptionCourante = $inscriptions->firstWhere('eleve_id', $eleve_id);
        $inscriptionId       = $inscriptionCourante?->id;

        /*
        |--------------------------------------------------------------------------
        | NOTES DES MATIÈRES (annee + trimestre + élève)
        |--------------------------------------------------------------------------
        */
        $notes = Note::with('matiere')
            ->where('inscription_id', $inscriptionId)
            ->where('trimestre_id', $trimestre)
            ->orderBy('matiere_id')
            ->get()
            ->groupBy('inscription_id');

        /*
        |--------------------------------------------------------------------------
        | MOYENNES GÉNÉRALES (annee + trimestre + élève)
        |--------------------------------------------------------------------------
        */
        $moyennes = Moyenne::where('inscription_id', $inscriptionId)
            ->where('trimestre_id', $trimestre)
            ->get()
            ->keyBy('inscription_id');

        /*
        |--------------------------------------------------------------------------
        | CONDUITES (annee + trimestre + élève)
        |--------------------------------------------------------------------------
        */
        $conduites = Conduite::where('inscription_id', $inscriptionId)
            ->where('trimestre_id', $trimestre)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | BULLETINS (annee + trimestre + élève)
        |--------------------------------------------------------------------------
        */
        $bulletins = Bulletin::where('inscription_id', $inscriptionId)
            ->where('trimestre_id', $trimestre)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MESSAGES RÉCENTS (liés au parent, indépendant de l'élève)
        |--------------------------------------------------------------------------
        */
        $messages = MessageParent::where('paren_id', $paren->id)
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS NON LUES (liées au parent)
        |--------------------------------------------------------------------------
        */
        $notificationsCount = NotificationParent::where('paren_id', $paren->id)
            ->where('lu', false)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | RETOUR VUE
        |--------------------------------------------------------------------------
        */
        return view('parens.dashboard', compact(
            'inscriptions',
            'messages',
            'notificationsCount',
            'moyennes',
            'conduites',
            'notes',
            'bulletins',
            'annees',
            'annee_id',
            'annee',
            'trimestre',
            'eleve_id',
            'paren'
        ));
    }
}