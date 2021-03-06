<?php


namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'room_id' => $this->room_id,
            'begin' => $this->begin,
            'end' => $this->end,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'permission' => ['update' => $this->when(Gate::allows('update_booking'), 'update'),
                             'crate' => $this->when(Gate::allows('create_booking'), 'create'),
                             'delete' => $this->when(Gate::allows('delete_booking'), 'delete'),],
        ];
    }
}
