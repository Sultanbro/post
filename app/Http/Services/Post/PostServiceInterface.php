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
}
