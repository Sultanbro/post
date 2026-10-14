<?php

namespace App\Providers;

use App\Http\Services\Post\PostService;
use App\Http\Services\Post\PostServiceInterface;
use App\Http\Services\WriteBase\Duty\DutySaveService;
use App\Http\Services\WriteBase\Duty\DutySaveServiceInterface;
use Illuminate\Support\ServiceProvider;

class DutyServiceProvide extends ServiceProvider
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
            DutySaveServiceInterface::class,
            DutySaveService::class
        );
    }
}
