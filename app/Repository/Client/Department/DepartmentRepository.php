<?php


namespace App\Repository\Client\Department;


use App\Models\Client\Department;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Department $model
     */
    public function __construct(Department $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    public function updateOrCreate(array $department)
    {
        $this->model->updateOrCreate($department);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function firstWhereId($id)
    {
        return $this->model->firstWhere('id', $id);
    }


    /**
     * @inheritDoc
     */
    public function getParentDepartment()
    {
        return $this->model->where('parent_id', 0)->get();
    }

    /**
     * @inheritDoc
     */
    public function getParentDepartmentByCompanyId($company_id)
    {
        return $this->model->where('id', $company_id)->get();
    }

    /**
     * @inheritDoc
     */
    public function firstWhereForeignIdCompanyId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }
}
