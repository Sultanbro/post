<?php
namespace App\Repository\Client\Department;

use Illuminate\Database\Eloquent\Model;
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

    /**
     * @return mixed
     */
    public function getParentDepartment();

    /**
     * @param $company_id
     * @return mixed
     */
    public function getParentDepartmentByCompanyId($company_id);

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstWhereForeignIdCompanyId($foreign_id, $company_id);

    /**
     * @param $slug
     * @return mixed
     */
    public function getAccessCompany($slug);

    /**
     * @param $department_id
     * @return mixed
     */
    public function getChildByDepartmentId($department_id);

}
