<?php


namespace App\Repository\User;


use App\Models\UserToken;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class UserTokenRepository extends BaseRepository implements UserTokenRepositoryInterface

{
    /**
     * UserRepository constructor.
     * @param UserToken $model
     */
    public function __construct(UserToken $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    /**
     * @param $user_id
     * @return mixed
     */
    public function findFromUserId($user_id)
    {
        return $this->model->firstWhere('user_id', $user_id);
    }

    /**
     * @param $refresh_token
     * @return mixed
     */
    public function findFromUserToken($refresh_token)
    {
        return $this->model->where('refresh_token', $refresh_token)->first();
    }

    /**
     * @param $access_token
     * @return mixed
     */
    public function findFromUserAccessToken($access_token)
    {
        return $this->model->firstWhere('access_token', $access_token);
    }
}
