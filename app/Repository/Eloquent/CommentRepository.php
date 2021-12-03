<?php


namespace App\Repository\Eloquent;


use App\Models\Comment;
use App\Repository\CommentRepositoryInterface;
use Illuminate\Cache\RetrievesMultipleKeys;
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
        return $this->model->where('post_id', $postId)->whereNull('parent_id')->get();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function deleteByParentId($id)
    {
        if ($this->model->firstWhere('parent_id', $id)) return $this->model->where('parent_id', $id)->delete();
        return true;
    }

    /**
     * @return mixed
     */
    public function getAllPostsComment()
    {
        return $this->model->whereNull('parent_id')->get();
    }
}
