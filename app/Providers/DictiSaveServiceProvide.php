<?php

namespace App\Providers;

use App\Http\Services\Post\PostService;
use App\Http\Services\Post\PostServiceInterface;
use App\Http\Services\WriteBase\DictisSaveService;
use App\Http\Services\WriteBase\DictisSaveServiceInterface;
use Illuminate\Support\ServiceProvider;

class DictiSaveServiceProvide extends ServiceProvider
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
            DictisSaveServiceInterface::class,
            DictisSaveService::class
        );
    }
}
