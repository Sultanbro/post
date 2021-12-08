<?php
namespace App\Repository\Client\Department;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;

interface DepartmentRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param array $department
     * @return mixed
     */
    public function updateOrCreate(array $department);

    /**
     * @param $id
     * @return mixed
     */
    public function firstWhereId($id);
}
