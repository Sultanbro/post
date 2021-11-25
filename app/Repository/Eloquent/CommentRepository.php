<?php


namespace App\Repository\Eloquent;


use App\Models\Comment;
use App\Repository\CommentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Comment $model
     */
    public function __construct(Comment $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $postId
     * @return mixed
     */
    public function getCommentsByPostId($postId)
    {
        return $this->model->where('post_id', $postId)->get();
    }

}
