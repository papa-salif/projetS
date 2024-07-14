<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use app\Http\Controllers\RatingController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use app\Http\Controllers\UserController;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        if (!Auth::check()) {
            // Permettre aux visiteurs d'accéder à certaines routes
            return $next($request);
        }

        if (!Auth::check() || !in_array(Auth::user()->role, $role)) {
            abort(403, 'Accès refusé');
        }

        // if (!Auth::check() || Auth::user()->role !== $role) {
            
        //     return redirect("/"); // ou toute autre redirection appropriée
        // }
        // if (!$request->user() || !$request->user()->hasAnyRole($role)) {
        //     abort(403, 'Unauthorized');
        // }
        
        return $next($request);    }
}
