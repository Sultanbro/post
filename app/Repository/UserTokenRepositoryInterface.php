<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

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
