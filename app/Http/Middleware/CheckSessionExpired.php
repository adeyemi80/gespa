<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSessionExpired
{
    public function handle(Request $request, Closure $next)
    {
        $publicRoutes = [
            '/',
            'login',
            'register',
            'dashboard',
            'password/*',
            'serviceReadm',
            'serviceReadp',
            'serviceReads',
            'registre',
            'check-session', 'medias', 'medias/*',
        ];

        if (!auth()->check() && !$request->is(...$publicRoutes)) {
            return redirect('/');
        }

        return $next($request);
    }
}
