<?php


namespace App\Http\Services\WriteBase;


interface ClientBaseServiceInterface
{
    /**
     * @param $users
     * @return mixed
     */
    public function saveClients($users);
}
