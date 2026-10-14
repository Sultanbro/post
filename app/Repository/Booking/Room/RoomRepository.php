<?php


namespace App\Repository\Booking\Room;


use App\Models\Booking\Room;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Booking\Room\RoomRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class RoomRepository extends BaseRepository implements RoomRepositoryInterface
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
