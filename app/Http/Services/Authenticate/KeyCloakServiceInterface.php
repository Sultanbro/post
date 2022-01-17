<?php


namespace App\Http\Services\Authenticate;


interface KeyCloakServiceInterface
{
    /**
     * @param $token
     * @return mixed
     */
    public function getUserInfo($token);

    /**
     * @param $email
     * @param $password
     * @return mixed
     */
    public function getToken($email, $password);

    /**
     * @param $refresh_token
     * @return mixed
     */
    public function refreshToken($refresh_token);

    /**
     * @param $email
     * @param $firstName
     * @param $lastName
     * @return mixed
     */
    public function registerUser($email, $firstName, $lastName);
}
