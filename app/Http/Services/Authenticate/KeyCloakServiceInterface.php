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
     * @return mixed
     */
    public function getUserByEmail($email);

    /**
     * @param $email
     * @param $firstName
     * @param $lastName
     * @return mixed
     */
    public function registerUser($email, $firstName, $lastName);

    /**
     * @param $password
     * @param $user_id
     * @return mixed
     */
    public function setPassword($password, $user_id);
}
