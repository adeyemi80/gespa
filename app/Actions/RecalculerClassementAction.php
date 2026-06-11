<?php

namespace App\Actions;

use App\Services\MoyenneService;

class RecalculerClassementAction
{
    public function __construct(
        protected MoyenneService $moyenneService
    ) {}

    public function execute(int $anneeId, int $classeId): array
    {
        return $this->moyenneService->calculerClassementAnnuel(
            $anneeId,
            $classeId
        );
    }
}