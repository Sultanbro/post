<?php

namespace App\Http\Services\Authenticate;

interface UserAuthServiceInterface
{
    /**
     * @param $user_id
     * @param $token
     * @return mixed
     */
    public function saveUserToken($user_id, $token);

    /**
     * @param $user
     * @return mixed
     */
    public function tokenResetPassword($user);

    /**
     * @param $token
     * @param $password
     * @return mixed
     */
    public function resetPassword($token, $password);

    /**
     * @param $email
     * @param $password
     * @return mixed
     */
    public function login($email, $password);
}
