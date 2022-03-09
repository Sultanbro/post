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
            'first_name' => isset($this->clientInfo->first_name) ? $this->clientInfo->first_name : null,
            'last_name' => isset($this->clientInfo->last_name) ? $this->clientInfo->last_name : null,
            'parent_name' => isset($this->clientInfo->parent_name) ? $this->clientInfo->parent_name : null,
            'short_name' => isset($this->clientInfo->short_name) ? $this->clientInfo->short_name : null,
            'full_name' => isset($this->clientInfo->full_name) ? $this->clientInfo->full_name : null,
            'birthday' => isset($this->clientInfo->birthday) ? $this->clientInfo->birthday : null,
            'avatar' => isset($this->clientInfo->avatar) ? $this->clientInfo->avatar : ['link' => 'storage/default/avatar/' . $this->clientInfo->sex . '/photo.jpg'],
        ];
    }
}
