<?php
namespace App\Repository\User\Staff;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface StaffUserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function getByForeignIdCompanyId($foreign_id, $company_id);
}
