<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kandidat;
use App\Observers\KandidatObserver;

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
        // Register Kandidat Observer
        Kandidat::observe(KandidatObserver::class);
    }
}