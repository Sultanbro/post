<?php


namespace App\Traits;


use App\Models\Permission;
use App\Models\Role;

trait QueryByRoleCompany
{
    public function getByRoleCompany($query)
    {
        foreach (request()->user()->roles as $role) {
            if ($role->company_id) {
                $company_id[] = $role->company_id;
            }else{
                return $query;
            }
        }

        return $query->whereIn('company_id', $company_id);
    }

    public function firstByRoleCompany($model)
    {
        foreach (request()->user()->roles as $role) {
            if ($model->company_id === $role->company_id) {
                return $model;
            }
            abort(403);
        }
    }
}
