<?php


namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class BookingUsersResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'booking_id' => $this->booking_id,
            'user_id' => $this->user_id,
            'permission' => ['update' => $this->when(Gate::allows('update_booking_users'), 'update'),
                             'crate' => $this->when(Gate::allows('create_booking_users'), 'create'),
                             'delete' => $this->when(Gate::allows('delete_booking_users'), 'delete'),],
        ];
    }
}
