<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\UserFullNameIdRecourse;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TreeResource extends JsonResource
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
            'full_name' => $this->full_name,
            'child' => TreeResource::collection($this->department),
            'users' => ClientResource::collection($this->users),
        ];
    }
}
