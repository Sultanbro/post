<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

interface EOrderRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstForeignIdCompanyId($foreign_id, $company_id);
}
