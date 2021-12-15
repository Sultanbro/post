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
}
