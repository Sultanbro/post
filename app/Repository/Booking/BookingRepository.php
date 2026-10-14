<?php


namespace App\Repository\Booking;


use App\Models\Booking\Booking;
use App\Repository\Eloquent\BaseRepository;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function whereBetween($request)
    {
        return $this->model->where('room_id',$request['room_id'])->whereBetween('end',
             [date('Y-m-d',strtotime($request['end'])).' 00:00:01', date('Y-m-d',strtotime($request['begin'])).' 23:59:59'])->get();
    }
}
