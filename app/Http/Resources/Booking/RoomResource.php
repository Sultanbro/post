<?php


namespace App\Http\Resources\Booking;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class RoomResource extends JsonResource
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
            'company_id' => $this->company_id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'address' => $this->address,
            'floor' => $this->floor,
            'cabinet' => $this->cabinet,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'permission' => ['update' => $this->when(Gate::allows('update_room'), 'update'),
                             'crate' => $this->when(Gate::allows('create_room'), 'create'),
                             'delete' => $this->when(Gate::allows('delete_room'), 'delete'),],
        ];
    }
}
