<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\UserFullNameIdRecourse;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'show_comment' => false,
            'user_info' => new UserFullNameIdRecourse($this->postsUser),
            'company_id' => $this->company_id,
            'group_id' => $this->group_id,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
            'post_files' => PostFileResource::collection($this->postFiles),
            'like_count' => count(data_get($this, 'like')),
            'liked' => $this->liked,
            'comments' => PostCommentResource::collection($this->postComments->take(1)),
            'comments_count' => $this->allComments->count(),
        ];
    }
}
