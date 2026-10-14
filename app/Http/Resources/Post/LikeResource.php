<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\UserFullNameIdRecourse;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
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
            'user_info' => new UserFullNameIdRecourse(data_get($this, 'likeUser')),
        ];
    }
}
