<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class PostFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $link = explode("/", $this->link);
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'link' => $this->link,
            'name' => $link[array_key_last($link)],
            ];
    }
}
