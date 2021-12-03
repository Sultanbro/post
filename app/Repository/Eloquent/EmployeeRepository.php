<?php


namespace App\Repository\Eloquent;


use App\Models\Reference\City;
use App\Models\Users\Employee;
use App\Repository\CityRepositoryInterface;
use App\Repository\EmployeeRepositoryInterface;

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
