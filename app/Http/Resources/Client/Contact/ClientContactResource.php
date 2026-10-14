<?php

namespace App\Http\Resources\Client\Contact;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientContactResource extends JsonResource
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
            'type' => $this->type,
            'phone' => $this->last_name,
            'contact' => $this->parent_name,
            'remark' => $this->short_name,
            'birthday' => $this->birthday,
        ];
    }
}
