<?php
namespace App\Repository\Post\Like;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface LikeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $parent_id
     * @param $type
     * @return mixed
     */
    public function byParentIdType($parent_id, $type);

    /**
     * @param $user_id
     * @param $type
     * @param $parent_id
     * @return mixed
     */
    public function liked($user_id, $type, $parent_id);

    /**
     * @param $parent_id
     * @param $type
     * @return mixed
     */
    public function liked_count($parent_id, $type);

    /**
     * @param array $req
     * @return mixed
     */
    public function firstByPostId(array $req);
}
