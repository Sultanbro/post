<?php

namespace App\Http\Services\Authenticate;


use App\Repository\UserRepositoryInterface;
use App\Repository\UserTokenRepositoryInterface;

class UserAuthService implements UserAuthServiceInterface
{
    /**
     * @var UserTokenRepositoryInterface
     */
    private $userTokenRepository;

    /**
     * UserAuthService constructor.
     * @param UserTokenRepositoryInterface $userTokenRepository
     */
    public function __construct(UserTokenRepositoryInterface $userTokenRepository)
    {
        $this->userTokenRepository = $userTokenRepository;
    }

    /**
     * @param $user
     * @param $token
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function saveUserToken($user, $token)
    {
        if (!$user_token = $this->userTokenRepository->findFromUserId($user->id)) {
            $this->userTokenRepository->create(['access_token' => $token['access_token'], 'refresh_token' => $token['refresh_token'], 'user_id' => $user->id, 'role_id' => 2]);

            return response()->json([
                'token_type' => 'Bearer',
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
                'created' => 1,
                'user_info' => $user,
            ], 200);
        }
        $this->userTokenRepository->update($user_token->id, ['access_token' => $token['access_token'], 'refresh_token' => $token['refresh_token'], 'role_id' => 1]);
        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'],
            'updated' => 1,
            'user_info' => $user,
        ], 200);
    }

}
