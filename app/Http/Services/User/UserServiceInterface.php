<?php

namespace App\Http\Services\User;

interface UserServiceInterface
{
    /**
     * @param array $clientCompanyId
     * @return mixed
     */
    public function getCareer(array $clientCompanyId);

    /**
     * @param $params
     * @return mixed
     */
    public function saveRole($params);
}
