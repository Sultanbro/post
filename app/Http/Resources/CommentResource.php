<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
          'content' => $this->content,
          'parent_id' => $this->parent_id,
          'post_id' => $this->post_id,
          'user_id' => new UserFullNameIdRecourse(data_get($this, 'commentUser')),
          'updated_by' => $this->updated_by,
          'updated' => $this->updated_at,
          'created' => $this->created_at,
          'like_count' => count(data_get($this, 'countLike')),
        ];
    }
}
