<?php
namespace App\Repository\User\Employee;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;

interface EmployeeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function firstById($id);
}
