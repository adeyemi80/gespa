<?php

namespace App\Console\Commands;

use App\Models\Annee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ActualiserAnneeEnCours extends Command
{
    /**
     * Nom et signature de la commande.
     */
    protected $signature = 'annees:actualiser-en-cours';

    /**
     * Description de la commande.
     */
    protected $description = "Active automatiquement l'année scolaire correspondant à la date du jour (entre debut et fin) et désactive les autres.";

    public function handle(): int
    {
        $aujourdHui = Carbon::today();

        DB::transaction(function () use ($aujourdHui) {

            // ✅ Recherche de l'année dont la période couvre la date du jour
            $anneeEnCours = Annee::whereDate('debut', '<=', $aujourdHui)
                ->whereDate('fin', '>=', $aujourdHui)
                ->first();

            if ($anneeEnCours) {

                // Désactive toutes les autres années actives
                Annee::where('id', '!=', $anneeEnCours->id)
                    ->where('en_cours', true)
                    ->update(['en_cours' => false]);

                if (! $anneeEnCours->en_cours) {
                    $anneeEnCours->update(['en_cours' => true]);
                    $this->info("Année activée : {$anneeEnCours->nom}");
                } else {
                    $this->info("Année déjà active : {$anneeEnCours->nom}");
                }

            } else {
                // Aucune année ne correspond à la date du jour (période de transition/vacances)
                Annee::where('en_cours', true)->update(['en_cours' => false]);
                $this->warn("Aucune année scolaire ne correspond à la date du jour ({$aujourdHui->toDateString()}). Toutes désactivées.");
            }
        });

        return Command::SUCCESS;
    }
}