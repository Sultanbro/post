<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'parent_name' => $this->parent_name,
            'short_name' => $this->short_name,
            'full_name' => $this->full_name,
//            'iin' => $this->iin,
//            'sex' => $this->sex,
            'address' => json_decode($this->address),
            'birthday' => $this->birthday,
//            'updated_by' => $this->updated_by,
//            'created_by' => $this->created_by,
//            'company_id' => $this->company_id,
        ];
    }
}
