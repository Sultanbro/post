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
            'full_name' => isset($this->clientInfo->full_name) ? $this->clientInfo->full_name : null,
            'avatar' => isset($this->clientInfo->avatar) ? $this->clientInfo->avatar : ['link' => 'storage/default/avatar/' . $this->clientInfo->sex . '/photo.jpg'],
        ];
    }
}
