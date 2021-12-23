<?php
namespace App\Repository\Post\Comment;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;

interface CommentRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * @param $postId
     * @return mixed
     */
    public function getCommentsByPostId($postId);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteByParentId($id);

    /**
     * @return mixed
     */
    public function getAllPostsComment();

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $postId
     * @return mixed
     */
    public function getAllCommentsByPostId($postId);

    /**
     * @param $id
     * @return mixed
     */
    public function getChildCommentById($id);

}
