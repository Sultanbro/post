<?php


namespace App\Repository\User\Avatar;

use App\Repository\Eloquent\EloquentRepositoryInterface;

interface AvatarRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $client_id
     * @return mixed
     */
    public function firstById($client_id);
}
