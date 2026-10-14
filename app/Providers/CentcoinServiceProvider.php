<?php

namespace App\Providers;

use App\Http\Services\Centcoin\CentcoinService;
use App\Http\Services\Centcoin\CentcoinServiceInterface;
use Illuminate\Support\ServiceProvider;

class CentcoinServiceProvider extends ServiceProvider
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
            CentcoinServiceInterface::class,
            CentcoinService::class
        );
    }
}
