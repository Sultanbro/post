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
     * @param $username
     * @param $password
     * @return mixed
     */
    public function getToken($username, $password);

    /**
     * @param $refresh_token
     * @return mixed
     */
    public function refreshToken($refresh_token);

    /**
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username);

    /**
     * @param $email
     * @param $firstName
     * @param $lastName
     * @param $username
     * @return mixed
     */
    public function registerUser($email, $firstName, $lastName,$username);

    /**
     * @param $password
     * @param $user_id
     * @return mixed
     */
    public function setPassword($password, $user_id);
}
