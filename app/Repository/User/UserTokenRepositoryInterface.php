<?php
namespace App\Repository\User;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface UserTokenRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $user_id
     * @return mixed
     */
    public function findFromUserId($user_id);

    /**
     * @param $refresh_token
     * @return mixed
     */
    public function findFromUserToken($refresh_token);

    /**
     * @param $access_token
     * @return mixed
     */
    public function findFromUserAccessToken($access_token);
}
