<?php

namespace App\Providers;

use App\Http\Services\Post\PostService;
use App\Http\Services\Post\PostServiceInterface;
use App\Http\Services\WriteBase\CareerUser\CareerUserSaveService;
use App\Http\Services\WriteBase\CareerUser\CareerUserSaveServiceInterface;
use App\Http\Services\WriteBase\Duty\DutySaveService;
use App\Http\Services\WriteBase\Duty\DutySaveServiceInterface;
use Illuminate\Support\ServiceProvider;

class CareerServiceProvide extends ServiceProvider
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
            CareerUserSaveServiceInterface::class,
            CareerUserSaveService::class
        );
    }
}
