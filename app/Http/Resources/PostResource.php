<?php

namespace App\Http\Resources;

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
            'user_info' => new UserFullNameIdRecourse(data_get($this, 'postsUser')),
            'company_id' => $this->company_id,
            'group_id' => $this->group_id,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
            'post_files' => data_get($this, 'postFiles'),
            'like_count' => count(data_get($this, 'like')),
            'liked' => $this->liked_count,
            'comments' => $this->postComments->take(3),
            'comments_count' => $this->allComments->count(),
        ];
    }
}
