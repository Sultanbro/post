<?php
namespace App\Repository\Reference\Region;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface RegionRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function firstByForeignId($id);

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstByForeignIdCompanyId($foreign_id, $company_id);
}
