<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inscription;
use App\Services\MoyenneService;

class RecalculerMoyennes extends Command
{
    /**
     * php artisan moyennes:recalculer {annee_id} {classe_id?}
     */
    protected $signature = 'moyennes:recalculer {annee_id} {classe_id?}';

    protected $description = 'Recalcule les moyennes de toutes les inscriptions d\'une année (et optionnellement d\'une classe)';

    public function handle(MoyenneService $moyenneService): int
    {
        $anneeId = (int) $this->argument('annee_id');
        $classeId = $this->argument('classe_id');

        $query = Inscription::where('annee_id', $anneeId);

        if ($classeId) {
            $query->where('classe_id', (int) $classeId);
        }

        $inscriptions = $query->get();

        $this->info("🔎 {$inscriptions->count()} inscription(s) trouvée(s). Recalcul en cours...");

        $bar = $this->output->createProgressBar($inscriptions->count());
        $bar->start();

        $errors = 0;

        foreach ($inscriptions as $inscription) {
            try {
                $moyenneService->calculerMoyennes($inscription->id);
            } catch (\Throwable $e) {
                $errors++;
                $this->error("\n❌ Erreur inscription {$inscription->id} : {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("✅ Terminé. {$errors} erreur(s) rencontrée(s).");

        return self::SUCCESS;
    }
}
