<?php


namespace App\Repository\User;


use App\Models\UserDetail;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class UserDetailsRepository extends BaseRepository implements UserDetailsRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param UserDetail $model
     */
    public function __construct(UserDetail $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function getByForeignId($id)
    {
        return $this->model->firstWhere('user_id', $id);
    }
}
