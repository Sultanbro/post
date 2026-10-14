<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Department\DepartmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerResource extends JsonResource
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
            'id' => $this->id,
            'company_id' => $this->company_id,
            'department' => new DepartmentResource($this->department),
            'client_id' => $this->client_id,
            'staff' => new StaffResource($this->staff),
        ];
    }
}
