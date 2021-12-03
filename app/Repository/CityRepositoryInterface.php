<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

interface CityRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstForeignCompanyId($foreign_id, $company_id);

}
