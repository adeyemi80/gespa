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
                'inscriptions'        => collect(),
                'messages'            => collect(),
                'notificationsCount' => 0,
                'moyennes'            => collect(),
                'conduites'           => collect(),
                'notes'               => collect(),
                'trimestre'           => 1,
                'paren'               => null,
            ]);
        }

        // Inscriptions des enfants
        $inscriptions = $paren->inscriptions()
            ->with([
                'eleve',
                'classe',
            ])
            ->get();

        // Trimestre sélectionné
        $trimestre = $request->get('trimestre', 1);

        /*
        |--------------------------------------------------------------------------
        | NOTES DES MATIÈRES
        |--------------------------------------------------------------------------
        */

        $notes = Note::with('matiere')
            ->whereIn(
                'inscription_id',
                $inscriptions->pluck('id')
            )
            ->where('trimestre_id', $trimestre)
            ->orderBy('matiere_id')
            ->get()
            ->groupBy('inscription_id');

        /*
        |--------------------------------------------------------------------------
        | MOYENNES GÉNÉRALES
        |--------------------------------------------------------------------------
        */

        $moyennes = Moyenne::whereIn(
                'inscription_id',
                $inscriptions->pluck('id')
            )
            ->where('trimestre_id', $trimestre)
            ->get()
            ->keyBy('inscription_id');

        /*
        |--------------------------------------------------------------------------
        | MESSAGES RÉCENTS
        |--------------------------------------------------------------------------
        */

        $messages = MessageParent::where('paren_id', $paren->id)
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS NON LUES
        |--------------------------------------------------------------------------
        */

        $notificationsCount = NotificationParent::where('paren_id', $paren->id)
            ->where('lu', false)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | CONDUITES
        |--------------------------------------------------------------------------
        */

        $conduites = Conduite::whereHas(
                'inscription.paren',
                function ($q) use ($paren) {

                    $q->where('parens.id', $paren->id);
                }
            )
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | BULLETINS
        |--------------------------------------------------------------------------
        */

        $bulletins = Bulletin::whereHas(
                'inscription.paren',
                function ($q) use ($paren) {

                    $q->where('parens.id', $paren->id);
                }
            )
            ->latest()
            ->get();

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
            'trimestre',
            'paren'
        ));
    }
}