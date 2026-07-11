<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // app/Console/Kernel.php

protected function schedule(Schedule $schedule): void
{
    $schedule->command('annees:actualiser-en-cours')
        ->dailyAt('00:05')
        ->withoutOverlapping();
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
