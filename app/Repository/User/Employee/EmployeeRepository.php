<?php


namespace App\Repository\Client\Employee;


use App\Models\Client\Employee;
use App\Repository\Eloquent\BaseRepository;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Employee $model
     */
    public function __construct(Employee $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
