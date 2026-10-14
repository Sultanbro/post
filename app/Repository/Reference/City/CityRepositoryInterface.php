<?php
namespace App\Repository\Reference\City;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface CityRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstForeignCompanyId($foreign_id, $company_id);

}
