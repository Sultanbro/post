<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

interface ClientRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstWhereForeignId($foreign_id, $company_id);


}
