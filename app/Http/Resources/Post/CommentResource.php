<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\UserFullNameIdRecourse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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
          'id' => $this->id,
          'content' => $this->content,
          'parent_id' => $this->parent_id,
          'post_id' => $this->post_id,
          'user_info' => new UserFullNameIdRecourse(data_get($this, 'commentUser')),
          'updated_by' => $this->updated_by,
          'updated' => $this->updated_at,
          'created' => $this->created_at,
          'is_liked' => is_null($this->isLiked) ? 0 : 1,
          'like_count' => count(data_get($this, 'countLike')),
          'link' => $this->link,
          'child_comments' => CommentResource::collection($this->comment),
            'permission' => ['update' => $this->when(Gate::allows('update_comment'), 'update'),
                             'crate' => $this->when(Gate::allows('create_comment'), 'create'),
                             'delete' => $this->when(Gate::allows('delete_comment'), 'delete'),],
        ];
    }
}
