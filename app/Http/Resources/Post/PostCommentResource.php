<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\CommentResource;
use App\Http\Resources\UserFullNameIdRecourse;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
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
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'post_id' => $this->post_id,
            'user_info' => new UserFullNameIdRecourse(data_get($this, 'commentUser')),
            'updated_by' => $this->updated_by,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
            'is_liked' => is_null($this->isLiked) ? 0 : 1,
            'like_count' => $this->countLike->count(),
        ];
    }
}
