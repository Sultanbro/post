<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

interface LikeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $parent_id
     * @param $type
     * @return mixed
     */
    public function byParentIdType($parent_id, $type);
}
