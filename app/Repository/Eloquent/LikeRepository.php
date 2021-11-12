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

}
