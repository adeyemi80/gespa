<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
   /**public function register(): void
{
    // Session expirée / CSRF
    $this->renderable(function (TokenMismatchException $e, $request) {

        return redirect('/')
            ->with('error', 'Votre session a expiré.');
    });

    // Utilisateur non connecté
    $this->renderable(function (AuthenticationException $e, $request) {

        return redirect('/')
            ->with('error', 'Veuillez vous reconnecter.');
    });
}*/

 public function register(): void
    {
        $this->renderable(function (TokenMismatchException $e, $request) {

            // Déconnecter proprement
            auth()->logout();

            // Invalider la session
            $request->session()->invalidate();

            // Régénérer le token CSRF
            $request->session()->regenerateToken();

            // Redirection accueil
            return redirect('dashboard')
                ->with('error', 'Votre session a expiré.');
        });
    }

     public function render($request, Throwable $exception)
{
    if ($exception instanceof TokenMismatchException) {
        return redirect('/')->withErrors(['session' => 'Votre session a expiré.']);
    }

    return parent::render($request, $exception);
}


}
