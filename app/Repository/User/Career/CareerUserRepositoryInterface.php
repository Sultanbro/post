<?php
namespace App\Repository\User\Career;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface CareerUserRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function getByForeign_idAndCompany_id($foreign_id, $company_id);

}
