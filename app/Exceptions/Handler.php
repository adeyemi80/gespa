<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
   

public function register(): void
{
    $this->reportable(function (Throwable $e) {
        //
    });

    // Session expirée (CSRF 419) → rediriger vers dashboard
    $this->renderable(function (TokenMismatchException $e, $request) {

        if ($request->hasHeader('X-Livewire')) {
            // Requête Livewire : réponse JSON que Livewire intercepte
            return response()->json([
                'effects' => ['redirect' => route('dashboard')],
            ], 200);
        }

        // Requête HTTP classique
        return redirect()->route('dashboard')
            ->with('info', 'Votre session a expiré. Veuillez vous reconnecter.');
    });

    // Fichier trop volumineux (PHP post_max_size dépassé)
    $this->renderable(function (PostTooLargeException $e, $request) {

        $message = 'Le fichier dépasse la taille maximale autorisée (50 Mo).';

        if ($request->hasHeader('X-Livewire')) {
            return response()->json([
                'effects' => ['redirect' => url()->previous()],
            ], 200);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
            ], 413);
        }

        return back()
            ->withErrors(['fichier' => $message])
            ->withInput();
    });
}
}