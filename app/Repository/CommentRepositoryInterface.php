<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

interface CommentRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * @param $postId
     * @return mixed
     */
    public function getCommentsByPostId($postId);
}
