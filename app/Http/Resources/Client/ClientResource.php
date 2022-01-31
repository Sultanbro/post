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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'parent_name' => $this->parent_name,
            'short_name' => $this->short_name,
            'full_name' => $this->full_name,
            'birthday' => $this->birthday,
            'avatar' => $this->avatar,
        ];
    }
}
