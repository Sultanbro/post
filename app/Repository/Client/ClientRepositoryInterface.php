<?php
namespace App\Repository\Client;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;

interface ClientRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstWhereForeignId($foreign_id, $company_id);

    /**
     * @param $company_id
     * @return mixed
     */
    public function getComingBDay($company_id);

    /**
     * @param $id
     * @return mixed
     */
    public function firstById($id);

    /**
     * @param $iin
     * @return mixed
     */
    public function firstClientByIin($iin);

}
