<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

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
