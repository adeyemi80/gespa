<?php

namespace App\Listeners;

use App\Events\NotesUpdated;
use App\Services\MoyenneService;

class RecalculerMoyennesListener
{
    public function __construct(
        protected MoyenneService $moyenneService
    ) {}

    public function handle(NotesUpdated $event): void
    {
        $this->moyenneService
            ->mettreAJourMoyennesParInscription(
                $event->inscriptionId
            );

        $this->moyenneService
            ->calculerClassementAnnuel(
                $event->anneeId,
                $event->classeId
            );
    }
}