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
     * @param $user_email
     * @return mixed
     */
    public function resetPassword($user_email);
}
