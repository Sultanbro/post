<?php

namespace App\Repository\Email\EmailPasswordReset;

use App\Repository\Eloquent\EloquentRepositoryInterface;

interface EmailPasswordResetRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * @param $user_id
     * @return mixed
     */
    public function firstByUserId($user_id);
}
