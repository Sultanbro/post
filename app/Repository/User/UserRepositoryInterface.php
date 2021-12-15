<?php
namespace App\Repository\User;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $email
     * @return mixed
     */
    public function userFromEmail($email);

    /**
     * @param $id
     * @return mixed
     */
    public function userById($id);

}
