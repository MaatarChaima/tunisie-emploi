<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            if (!auth()->user()->isValidated()) {
                // Allow profile edit so they can update info
                if (!$request->routeIs('profile.*')) {
                    return redirect()->route('profile.edit')->with('error', 'Votre compte est en attente de validation par un administrateur.');
                }
            }
        }

        return $next($request);
    }
}
