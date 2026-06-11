<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Models\MessageParent;
use App\Models\NotificationParent;
use App\Models\Bulletin;
use App\Models\Conduite;
use App\Models\Note;
use App\Models\Moyenne;

class ParenDashboardApiController extends Controller
{
    protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Dashboard parent API
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Parent lié au user connecté
        $paren = $user->parens()->first();

        // Aucun parent trouvé
        if (!$paren) {

            return response()->json([
                'success' => false,
                'message' => 'Aucun parent associé à cet utilisateur.',
                'data' => [
                    'inscriptions'        => [],
                    'messages'            => [],
                    'notificationsCount' => 0,
                    'moyennes'            => [],
                    'conduites'           => [],
                    'notes'               => [],
                    'bulletins'           => [],
                    'trimestre'           => 1,
                    'paren'               => null,
                ]
            ], 404);
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
        | NOTES
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
        | MOYENNES
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
        | MESSAGES
        |--------------------------------------------------------------------------
        */

        $messages = MessageParent::where('paren_id', $paren->id)
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
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
        | RETOUR JSON
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'data' => [
                'paren'               => $paren,
                'trimestre'           => $trimestre,
                'inscriptions'        => $inscriptions,
                'notes'               => $notes,
                'moyennes'            => $moyennes,
                'messages'            => $messages,
                'notificationsCount' => $notificationsCount,
                'conduites'           => $conduites,
                'bulletins'           => $bulletins,
            ]
        ]);
    }
}