<?php


namespace App\Providers;


use App\Http\Services\Booking\BookingService;
use App\Http\Services\Booking\BookingServiceInterface;
use App\Repository\Booking\Room\RoomRepository;
use App\Repository\Booking\Room\RoomRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class BookingServiceProvider extends ServiceProvider
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
            BookingServiceInterface::class,
            BookingService::class
        );
    }
}
