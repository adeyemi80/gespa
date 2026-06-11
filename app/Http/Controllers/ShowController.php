<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Note;
use App\Models\Paren;
use Illuminate\Support\Facades\Auth;
use App\Models\Inscription;
use App\Services\NotificationService;
use App\Models\MessageParent;
use App\Models\NotificationParent;
use App\Models\Bulletin;
use App\Models\Conduite;

class ShowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

      public function index(Request $request)
    {
        $parent = Auth::user()->paren; // le parent connecté

        // ✅ Tous les enfants de ce parent
        $eleves = Eleve::where('paren_id', $parent->id)->pluck('id');

        // ✅ Inscriptions de ces enfants
        $inscriptions = Inscription::with(['eleve', 'classe'])
            ->whereIn('eleve_id', $eleves)
            ->get();

        // ✅ Notes groupées par inscription
        $notes = Note::with('matiere')
            ->whereIn('inscription_id', $inscriptions->pluck('id'))
            ->get()
            ->groupBy('inscription_id');

        // ✅ Conduites récentes
        $conduites = Conduite::with('inscription.eleve')
            ->whereIn('inscription_id', $inscriptions->pluck('id'))
            ->orderByDesc('created_at')
            ->get();

        // ✅ Messages → filtrer par eleve_id
        $messages = MessageParent::with('eleve')
            ->whereIn('eleve_id', $eleves)
            ->orderByDesc('created_at')
            ->get();

        // ✅ Notifications non lues pour le parent connecté
        $notificationsCount = NotificationParent::where('paren_id', $parent->id)
    ->where('lu', false)
    ->count();


        // ✅ Trimestre sélectionné (1 par défaut)
        $trimestre = $request->get('trimestre', 1);

        return view('show.index', [
            'inscriptions' => $inscriptions,
            'notes' => $notes,
            'conduites' => $conduites,
            'messages' => $messages,
            'notificationsCount' => $notificationsCount,
            'trimestre' => $trimestre,
        ]);
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('show.create');
    }
    public function create2()
    {
        return view('show.admin');
    }
    public function create3()
    {
        return view('show.import');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        return view('show.sotre');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('show.show');
    }
    public function show2()
    {
        return view('show.show2');
    }
 public function show3()
    {
        return view('show.register');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('show.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        return view('show.update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
       return view('show.destroy');
    }
}
