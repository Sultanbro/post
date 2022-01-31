<?php


namespace App\Repository\Booking\BookingUsers;

use App\Models\Booking\BookingUsers;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;

class BookingUsersRepository extends BaseRepository implements BookingUsersRepositoryInterface
{

    public function __construct(BookingUsers $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
