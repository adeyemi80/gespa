<?php

namespace App\Listeners;

use App\Events\BulletinGenerated;
use Illuminate\Support\Facades\Log;

class RafraichirBulletinsListener
{
    public function handle(BulletinGenerated $event): void
    {
        Log::info(
            'Bulletin généré',
            [
                'inscription_id' => $event->inscriptionId,
                'trimestre_id' => $event->trimestreId,
                'date' => now(),
            ]
        );

        // futur :
        // Cache::forget(...)
        // Notification::send(...)
    }
}