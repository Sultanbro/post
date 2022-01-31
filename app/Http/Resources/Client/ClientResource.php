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
            'first_name' => $this->clientInfo->first_name,
            'last_name' => $this->clientInfo->last_name,
            'parent_name' => $this->clientInfo->parent_name,
            'short_name' => $this->clientInfo->short_name,
            'full_name' => $this->clientInfo->full_name,
            'birthday' => $this->clientInfo->birthday,
            'avatar' => $this->clientInfo->avatar,
        ];
    }
}
