<?php

namespace App\Providers;

use App\Http\Services\WriteBase\Region\RegionSaveService;
use App\Http\Services\WriteBase\Region\RegionSaveServiceInterface;
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
