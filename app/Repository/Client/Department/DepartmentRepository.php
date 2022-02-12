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

    /**
     * @param array $department
     * @return mixed|void
     */
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
     * @return mixed
     */
    public function getParentDepartment()
    {
        return $this->model->where('parent_id', 0)->get();
    }

    /**
     * @return mixed
     */
    public function getCompanies()
    {
        return $this->model->where('parent_id', 1)->get();
    }

    /**
     * @param $company_id
     * @return mixed
     */
    public function getParentDepartmentByCompanyId($company_id)
    {
        return $this->model->where('id', $company_id)->get();
    }

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstWhereForeignIdCompanyId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getAccessCompany($slug)
    {
        $company_id = [];
        foreach (request()->user()->roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission->slug === $slug) $company_id[] =  $role->company_id;
                if ($role->company_id) continue;
                foreach ($this->getCompanies() as $dept) {
                    $company_id[] = $dept->id;
                }
                return ['access' => array_unique($company_id)];
            }
        }
        return ['access' => array_unique($company_id)];
    }

    /**
     * @param $department_id
     * @return mixed
     */
    public function getChildByDepartmentId($department_id)
    {
        return $this->model->where('parent_id', $department_id)->get();
    }
}
