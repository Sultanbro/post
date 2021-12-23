<?php


namespace App\Http\Services\WriteBase\Client;


interface ClientBaseServiceInterface
{
    /**
     * @param $clients
     * @return mixed
     */
    public function saveClients( $clients);

    /**
     * @param $e_orders
     * @return mixed
     */
    public function acceptEOrder($e_orders);

    /**
     * @param $req
     * @return mixed
     */
    public function acceptAvatar($req);

    /**
     * @param $req
     * @param $user_id
     * @return mixed
     */
    public function saveAvatar($req, $user_id);
}
