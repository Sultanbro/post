<?php
namespace App\Repository\User;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface UserDetailsRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getByForeignId($id);

}
