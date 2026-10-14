<?php
namespace App\Repository\Reference\Dicti;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface DictiRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param string $name
     * @param int $foreign_id
     * @return mixed
     */
    public function compareInNameAndParentId(string $name, int $foreign_id);

    /**
     * @param int $foreign_id
     * @param int $company_id
     * @return mixed
     */
    public function firstWhereForeignIdCompanyId(int $foreign_id, int $company_id);
}
