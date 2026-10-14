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
     * @param $client_id
     * @return mixed
     */
    public function saveAvatar($req, $client_id);

    /**
     * @param $params
     * @return mixed
     */
    public function userDetails($params);

    /**
     * @param $username
     * @param int $var
     * @return mixed
     */
    public function createUsername($username, $var = 0);

    /**
     * @param $params
     * @return mixed
     */
    public function saveFile($params);

    /**
     * @param $fileName
     * @param $filePath
     * @param $content
     * @return mixed
     */
    public function saveStorage($fileName, $filePath, $content);

    /**
     * @param $model
     * @return mixed
     */
    public function deleteAvatars($model);
}
