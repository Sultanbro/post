<?php

namespace App\Providers;

use App\Http\Services\Authenticate\KeyCloakService;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use Illuminate\Support\ServiceProvider;

class KeyCloakServiceProvider extends ServiceProvider
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
            KeyCloakServiceInterface::class,
            KeyCloakService::class
        );
    }
}
