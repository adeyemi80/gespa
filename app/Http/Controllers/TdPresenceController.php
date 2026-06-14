<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TdSeance;
use App\Models\TdPresence;

class TdPresenceController extends Controller
{
    public function show(TdSeance $seance)
{
    $seance->load(['classe', 'annee']);

    $presences = TdPresence::with('eleve')
        ->where('td_seance_id', $seance->id)
        ->join('eleves', 'td_presences.eleve_id', '=', 'eleves.id')
        ->orderBy('eleves.nom')
        ->orderBy('eleves.prenom')
        ->select('td_presences.*', 'eleves.nom', 'eleves.prenom')
        ->get();

    $nbPresents = $presences->where('present', true)->count();
    $nbAbsents  = $presences->where('present', false)->count();

    return view('td_presences.show', compact('seance', 'presences', 'nbPresents', 'nbAbsents'));
}
}
