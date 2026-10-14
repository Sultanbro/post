<?php

namespace App\Http\Resources\Centcoin;

use App\Http\Resources\UserFullNameIdRecourse;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CentcoinResource extends JsonResource
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
            'id'=> $this->id ,
            'type_id'=> $this->type_id,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'user_id' => $this->user_id,
            'updated' => $this->updated_at,
            'created' => $this->created_at,

        ];
    }
}
