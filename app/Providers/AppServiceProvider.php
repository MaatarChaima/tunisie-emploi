<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\View\Composers\NavBadgesComposer;

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
    public function boot(): void
    {
        // Dates en français (translatedFormat, diffForHumans)
        Carbon::setLocale(config('app.locale'));

        Paginator::defaultView('pagination::bootstrap-5');

        View::composer('layouts.navigation', NavBadgesComposer::class);

        Gate::define('isCompany', function (User $user) {
            return $user->isCompany();
        });

        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('isCandidate', function (User $user) {
            return $user->isCandidate();
        });
    }
}
