<?php

namespace App\Providers;

use App\Models\Annee;
use App\Observers\AnneeObserver;
use App\Models\Inscription;
use App\Observers\InscriptionObserver;
use App\Observers\UserObserver;
use App\Models\Note;
use App\Observers\NoteObserver;
//use App\Observers\ConduiteObserver;
use App\Models\User;  
//use App\Models\Conduite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
 use Illuminate\Support\Facades\View;
use App\Models\NotificationParent;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // AppServiceProvider.php
public function boot()
{
    Annee::observe(AnneeObserver::class);
    Note::observe(NoteObserver::class);
    //Conduite::observe(ConduiteObserver::class);
    //Frais::observe(FraisObserver::class);
    Inscription::observe(InscriptionObserver::class);
    //User::observe(UserObserver::class);
    // Eleve::observe(EleveObserver::class);  // ✅ Ajouté
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


// Dans app/Providers/AppServiceProvider.php
// Ajoutez ceci dans la méthode boot()


}




