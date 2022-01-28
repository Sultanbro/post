<?php


namespace App\Repository\User;


use App\Models\User;
use App\Repository\Eloquent\BaseRepository;
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
    public function userById($id)
    {
        return $this->model->firstWhere('id', $id);
    }

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function getByForeignIdAndCompany_id($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return $this->model->firstWhere('username', $username);
    }

    /**
     * @param $iin
     * @return mixed
     */
    public function firstClientByIin($iin)
    {
        return $this->model->firstWhere('iin', $iin);
    }

    /**
     * @param $username
     * @return mixed
     */
    public function firstUserByUsername($username)
    {
        return $this->model->firstWhere('username', $username);
    }
}
