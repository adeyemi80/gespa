<?php

namespace App\Http\Controllers;

use App\Models\Benefice;
use App\Models\Investissement;
use App\Models\Investisseur;
use App\Models\PaiementBenefice;
use App\Models\RetraitCapital;
use App\Models\Versement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tableau de bord principal.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Statistiques générales
        |--------------------------------------------------------------------------
        */

        $nbInvestisseurs = Investisseur::count();

        $nbInvestissements = Investissement::count();

        $nbInvestissementsActifs = Investissement::where('statut', 'actif')->count();

        $capitalInvesti = Investissement::sum('montant');

        $versements = Versement::sum('montant');

        $benefices = Benefice::sum('montant');

        $beneficesPayes = PaiementBenefice::sum('montant');

        $capitalRetire = RetraitCapital::sum('montant');

        $capitalRestant = $capitalInvesti - $capitalRetire;

        $beneficesRestants = $benefices - $beneficesPayes;

        /*
        |--------------------------------------------------------------------------
        | Investissements par mois
        |--------------------------------------------------------------------------
        */

        $investissementsMensuels = Investissement::select(
                DB::raw("EXTRACT(MONTH FROM date_investissement) as mois"),
                DB::raw("SUM(montant) as total")
            )
            ->groupBy(DB::raw("EXTRACT(MONTH FROM date_investissement)"))
            ->orderBy(DB::raw("EXTRACT(MONTH FROM date_investissement)"))
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Bénéfices par mois
        |--------------------------------------------------------------------------
        */

        $beneficesMensuels = Benefice::select(
                DB::raw("EXTRACT(MONTH FROM date_fin) as mois"),
                DB::raw("SUM(montant) as total")
            )
            ->groupBy(DB::raw("EXTRACT(MONTH FROM date_fin)"))
            ->orderBy(DB::raw("EXTRACT(MONTH FROM date_fin)"))
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Derniers investissements
        |--------------------------------------------------------------------------
        */

        $derniersInvestissements = Investissement::with('investisseur')
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Derniers versements
        |--------------------------------------------------------------------------
        */

        $derniersVersements = Versement::with([
            'investissement.investisseur'
        ])
        ->latest()
        ->take(10)
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Derniers bénéfices
        |--------------------------------------------------------------------------
        */

        $derniersBenefices = Benefice::latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Investisseurs ayant le plus investi
        |--------------------------------------------------------------------------
        */

        $topInvestisseurs = Investisseur::withSum(
                'investissements',
                'montant'
            )
            ->orderByDesc('investissements_sum_montant')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Répartition des investissements
        |--------------------------------------------------------------------------
        */

        $repartition = Investisseur::withSum(
                'investissements',
                'montant'
            )
            ->get();

        return view(
            'investissements.dashboard',
            compact(

                'nbInvestisseurs',

                'nbInvestissements',

                'nbInvestissementsActifs',

                'capitalInvesti',

                'capitalRetire',

                'capitalRestant',

                'versements',

                'benefices',

                'beneficesPayes',

                'beneficesRestants',

                'investissementsMensuels',

                'beneficesMensuels',

                'derniersInvestissements',

                'derniersVersements',

                'derniersBenefices',

                'topInvestisseurs',

                'repartition'
            )
        );
    }
}