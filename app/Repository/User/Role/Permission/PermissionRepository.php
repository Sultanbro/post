<?php


namespace App\Repository\User\Role\Permission;


use App\Models\Permission;
use App\Repository\Eloquent\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
