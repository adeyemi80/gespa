<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BulletinGenerated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $inscriptionId,
        public int $trimestreId
    ) {}
}