<?php

namespace App\Providers;

use App\Http\Services\WriteBase\Client\ClientBaseService;
use App\Http\Services\WriteBase\Client\ClientBaseServiceInterface;
use Illuminate\Support\ServiceProvider;

class ClientBaseServiceProvider extends ServiceProvider
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
            ClientBaseServiceInterface::class,
            ClientBaseService::class
        );
    }
}
