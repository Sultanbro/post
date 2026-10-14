<?php


namespace App\Repository\User\Role\PermissionGroup;


use App\Models\PermissionGroup;
use App\Repository\Eloquent\BaseRepository;

class PermissionGroupRepository extends BaseRepository implements PermissionGroupRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param PermissionGroup $model
     */
    public function __construct(PermissionGroup $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
