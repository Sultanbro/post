<?php


namespace App\Repository\User\Employee;


use App\Models\Client\UserStory\Employee;
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
