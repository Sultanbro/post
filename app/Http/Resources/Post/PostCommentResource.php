<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\UserFullNameIdRecourse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostCommentResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
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
            'child_comments' => CommentResource::collection($this->comment->take(1)),
            'permission' => ['update' => $this->when(Gate::allows('update_post') or Auth::id() === $this->user_id, 'update'),
                             'crate' => $this->when(Gate::allows('create_post'), 'create'),
                             'delete' => $this->when(Gate::allows('delete_post') or Auth::id() === $this->user_id, 'delete'),],
        ];
    }
}
