<?php
namespace App\Repository\Client\EOrder;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;

interface EOrderRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstForeignIdCompanyId($foreign_id, $company_id);
}
