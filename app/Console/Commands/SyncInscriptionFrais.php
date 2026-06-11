<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inscription;
use App\Models\Frais;
use Illuminate\Support\Facades\DB;

class SyncInscriptionFrais extends Command
{
    protected $signature = 'sync:inscription-frais';
    protected $description = 'Synchroniser inscription_frais à partir de classe_frais et annee_frais';

    public function handle()
    {
        $this->info('Synchronisation en cours...');

        $count = 0;

        $inscriptions = Inscription::all();

        foreach ($inscriptions as $inscription) {

            $fraisClasse = DB::table('classe_frais')
                ->where('classe_id', $inscription->classe_id)
                ->pluck('frais_id');

            $fraisAnnee = DB::table('annee_frais')
                ->where('annee_id', $inscription->annee_id)
                ->pluck('frais_id');

            $fraisIds = $fraisClasse->merge($fraisAnnee)->unique();

            foreach ($fraisIds as $fraisId) {

                $frais = Frais::find($fraisId);
                if (!$frais) continue;

                DB::table('inscription_frais')->updateOrInsert(
                    [
                        'inscription_id' => $inscription->id,
                        'frais_id'       => $frais->id,
                    ],
                    [
                        'annee_id'      => $inscription->annee_id,
                        'montant_frais' => $frais->montant,
                        'montant_paye'  => 0,
                        'reste'         => $frais->montant,
                        'statut'        => 'non_payé',
                        'est_arriere'   => false,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );

                $count++;
            }
        }

        $this->info("Synchronisation terminée : {$count} lignes traitées.");
    }
}
