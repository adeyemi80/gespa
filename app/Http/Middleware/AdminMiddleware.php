<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        // Remplacez par votre logique admin
        // Exemple 1 : champ `role` dans users
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès admin requis');
        }
        
        // Exemple 2 : champ `is_admin` boolean
        // if (!Auth::user()->is_admin) {
        //     abort(403);
        // }
        
        return $next($request);
    }
}
