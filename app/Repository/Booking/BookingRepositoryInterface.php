<?php


namespace App\Repository\Booking;


use App\Repository\Eloquent\EloquentRepositoryInterface;

interface BookingRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $request
     * @return mixed
     */
    public function whereBetween($request);
}
