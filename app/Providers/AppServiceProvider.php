<?php

namespace App\Providers;

use App\Models\Annee;
use App\Observers\AnneeObserver;
use App\Models\Inscription;
use App\Observers\InscriptionObserver;
use App\Models\Note;
use App\Observers\NoteObserver;
use App\Models\Versement;
use App\Observers\VersementObserver;
use App\Models\Paiement;
use App\Observers\PaiementObserver;
use App\Models\NotificationParent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Annee::observe(AnneeObserver::class);
        Note::observe(NoteObserver::class);
        Versement::observe(VersementObserver::class);
        Paiement::observe(PaiementObserver::class);
        Inscription::observe(InscriptionObserver::class);

        View::composer('*', function ($view) {
            $count = 0;
            if (auth()->check()) {
                $count = NotificationParent::where('paren_id', auth()->id())
                    ->where('lu', false)
                    ->count();
            }
            $view->with('notificationsCount', $count);
        });
    }
}