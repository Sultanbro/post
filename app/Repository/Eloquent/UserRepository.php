<?php


namespace App\Repository\Eloquent;


use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function userFromEmail($email)
    {
        return $this->model->firstWhere('email', $email);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function userFromId($id)
    {
        return $this->model->firstWhere('user_id', $id);
    }
}
