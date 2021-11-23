<?php


namespace App\Repository\Eloquent;


use App\Models\Like;
use App\Repository\LikeRepositoryInterface;

class LikeRepository extends BaseRepository implements LikeRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Like $model
     */
    public function __construct(Like $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function byParentIdType($parent_id, $type)
    {
        return $this->model->where('parent_id', $parent_id)->where('type', $type)->get();
    }

    /**
     * @param $user_id
     * @param $type
     * @param $parent_id
     * @return mixed
     */
    public function liked($user_id, $type, $parent_id)
    {
        return $this->model->where('parent_id', $parent_id)->where('type', $type)->where('user_id', $user_id)->get();
    }


    /**
     * @inheritDoc
     */
    public function liked_count($parent_id, $type)
    {
        return $this->model->where('parent_id', $parent_id)->where('type', $type)->count();
    }

    public function firstByPostId($req)
    {
        return $this->model->whereParent_idAndTypeAndUser_id($req['parent_id'], $req['type'], $req['user_id'])->first();
    }
}
