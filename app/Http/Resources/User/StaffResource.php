<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'foreign_id' => $this->foreign_id,
            'duty' => new DutyResource($this->duty),
            'date_beg' => $this->date_beg,
            'date_end' => $this->date_end,
        ];
    }
}
