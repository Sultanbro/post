<?php

namespace App\Providers;

use App\Http\Services\Authenticate\KeyCloakService;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Http\Services\WriteBase\City\CitiesSaveService;
use App\Http\Services\WriteBase\City\CitiesSaveServiceInterface;
use Illuminate\Support\ServiceProvider;

class CitiesSaveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            CitiesSaveServiceInterface::class,
            CitiesSaveService::class
        );
    }
}
