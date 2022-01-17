<?php

namespace App\Http\Services\Post;

interface PostServiceInterface
{
    /**
     * @param array $params
     * @param int $id
     * @return mixed
     */
    public function saveFiles(array $params, int $id);

    /**
     * @param $req
     * @param $user_id
     * @return mixed
     */
    public function store($req, $user_id);

    /**
     * @param $req
     * @param $id
     * @return mixed
     */
    public function saveCommentFile($req, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteComment($id);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteCommentsStore($id);
}
