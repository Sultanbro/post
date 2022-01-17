<?php
namespace App\Repository\Client\ClientContact;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;

interface ClientContactRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getByClientId($id);
}
