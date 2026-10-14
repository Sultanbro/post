<?php

namespace App\Http\Resources\Centcoin;

use App\Http\Resources\UserFullNameIdRecourse;
use Illuminate\Http\Resources\Json\JsonResource;

class CentcoinApplyResource extends JsonResource
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
            'total'=> $this->total,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'user_info' =>new UserFullNameIdRecourse($this->applyUser),
            'updated' => $this->updated_at,
            'created' => $this->created_at,

        ];
    }
}
