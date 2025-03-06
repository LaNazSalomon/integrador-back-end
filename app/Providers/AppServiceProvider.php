<?php

namespace App\Providers;

use App\Business\Interfaces\DatesInterface;
use App\Business\Services\ReservationsMadeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Aca vamos a declarar todas las inyecciones de depencencia
     */
    public function register(): void
    {
        $this->app->bind(DatesInterface::class,ReservationsMadeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
