<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

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
