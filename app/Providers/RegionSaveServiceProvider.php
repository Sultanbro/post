<?php

namespace App\Providers;

use App\Http\Services\Authenticate\KeyCloakService;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Http\Services\WriteBase\CitiesSaveService;
use App\Http\Services\WriteBase\CitiesSaveServiceInterface;
use App\Http\Services\WriteBase\RegionSaveService;
use App\Http\Services\WriteBase\RegionSaveServiceInterface;
use Illuminate\Support\ServiceProvider;

class RegionSaveServiceProvider extends ServiceProvider
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
            RegionSaveServiceInterface::class,
            RegionSaveService::class
        );
    }
}
