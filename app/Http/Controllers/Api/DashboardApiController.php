<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Moyenne;
use App\Models\Paiement;
use App\Models\User;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $annee = Annee::orderBy('id', 'desc')->first();

        $totalEleves = Inscription::where('annee_id', $annee?->id)->count();

        $totalClasses = Classe::count();

        $totalEnseignants = User::where('role', 'enseignant')->count();

        $totalParents = User::where('role', 'parent')->count();

        // Taux de réussite global
        $moyennes = Moyenne::whereHas('inscription', function ($q) use ($annee) {
            $q->where('annee_id', $annee?->id);
        })->get();

        $tauxReussite = $moyennes->count() > 0
            ? round(
                $moyennes->where('moyenne_trimestrielle', '>=', 10)->count()
                / $moyennes->count() * 100, 2
              )
            : 0;

        // Moyenne générale
        $moyenneGenerale = $moyennes->count() > 0
            ? round($moyennes->avg('moyenne_trimestrielle'), 2)
            : 0;

        // Répartition garçons / filles
        $garcons = Inscription::where('annee_id', $annee?->id)
            ->whereHas('eleve', fn($q) => $q->where('sexe', 'M'))
            ->count();

        $filles = Inscription::where('annee_id', $annee?->id)
            ->whereHas('eleve', fn($q) => $q->where('sexe', 'F'))
            ->count();

        // Effectif par classe
        $effectifParClasse = Classe::withCount([
            'inscriptions' => fn($q) => $q->where('annee_id', $annee?->id)
        ])->orderBy('ordre')->get()->map(fn($c) => [
            'classe'   => $c->nom,
            'effectif' => $c->inscriptions_count,
        ]);

        // Paiements
        $totalPaiements = Paiement::whereHas('inscription', function ($q) use ($annee) {
    $q->where('annee_id', $annee?->id);
})->sum('montant_verse');

        return response()->json([
            'annee'            => $annee?->nom,
            'total_eleves'     => $totalEleves,
            'total_classes'    => $totalClasses,
            'total_enseignants'=> $totalEnseignants,
            'total_parents'    => $totalParents,
            'taux_reussite'    => $tauxReussite,
            'moyenne_generale' => $moyenneGenerale,
            'garcons'          => $garcons,
            'filles'           => $filles,
            'effectif_classes' => $effectifParClasse,
            'total_paiements'  => $totalPaiements,
        ]);
    }
}