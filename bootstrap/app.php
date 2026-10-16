<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'company' => \App\Http\Middleware\CompanyMiddleware::class,
            'check_status' => \App\Http\Middleware\CheckUserStatus::class,
            'check_validation' => \App\Http\Middleware\CheckValidation::class,
        ]);
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckUserStatus::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Jeton CSRF expiré (session expirée ou purgée) : jamais de page 419 brute.
        // Laravel convertit TokenMismatchException en HttpException 419 avant ce rendu.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            $message = 'Votre session a expiré. Veuillez réessayer.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 419);
            }

            // Déconnexion : on déconnecte quand même et on revient à la liste des offres
            if ($request->routeIs('logout')) {
                \Illuminate\Support\Facades\Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('job-offers.index')->with('info', 'Vous êtes déconnecté.');
            }

            // Autres formulaires : retour à la page précédente avec les saisies (sans mots de passe)
            return back()
                ->withInput($request->except(['_token', 'password', 'password_confirmation', 'current_password']))
                ->with('error', $message);
        });
    })->create();
