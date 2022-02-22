<?php


namespace App\Http\Services\Booking;


interface BookingServiceInterface
{
    /**
     * Booking
     */
    public function index();

    /**
     * @param $request
     * @param $user_id
     * @return mixed
     */
    public function store($request, $user_id);

    /**
     * @param $id
     * @return mixed
     */
    public function show($id);
}
