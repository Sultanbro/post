<?php

namespace App\Providers;

use App\Http\Services\Authenticate\UserAuthService;
use App\Http\Services\Authenticate\UserAuthServiceInterface;
use Illuminate\Support\ServiceProvider;

class UserAuthtServiceProvide extends ServiceProvider
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
            UserAuthServiceInterface::class,
            UserAuthService::class
        );
    }
}
