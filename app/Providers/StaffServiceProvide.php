<?php

namespace App\Providers;

use App\Http\Services\Post\PostService;
use App\Http\Services\Post\PostServiceInterface;
use App\Http\Services\WriteBase\Duty\DutySaveService;
use App\Http\Services\WriteBase\Duty\DutySaveServiceInterface;
use App\Http\Services\WriteBase\Staff\StaffSaveService;
use App\Http\Services\WriteBase\Staff\StaffSaveServiceInterface;
use Illuminate\Support\ServiceProvider;

class StaffServiceProvide extends ServiceProvider
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
            StaffSaveServiceInterface::class,
            StaffSaveService::class
        );
    }
}
