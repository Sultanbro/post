<?php

namespace App\Http\Services\Authenticate;


use App\Repository\User\UserRepositoryInterface;
use App\Repository\User\UserTokenRepositoryInterface;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class UserAuthService implements UserAuthServiceInterface
{
    /**
     * @var UserTokenRepositoryInterface
     */
    private $userTokenRepository;
    /**
     * @var KeyCloakServiceInterface
     */
    private $keyCloakService;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserAuthService constructor.
     * @param UserTokenRepositoryInterface $userTokenRepository
     * @param KeyCloakServiceInterface $keyCloakService
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserTokenRepositoryInterface $userTokenRepository, KeyCloakServiceInterface $keyCloakService, UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->keyCloakService = $keyCloakService;
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

    public function tokenResetPassword($user)
    {
        $token = [];
        $rand = Str::random(59);
        $token['access_token'] = $rand;
        $token['refresh_token'] = $rand;

        if (!$this->saveUserToken($user, $token)->status() == 200) {
            return 'not save';
        }

        return $token['access_token'];

    }

    public function resetPassword($token, $password)
    {
        try {

            if ($userToken = $this->userTokenRepository->findFromUserAccessToken($token)) {
                $userKeyCloak = $this->keyCloakService->getUserByEmail($userToken->user->email);
                $this->keyCloakService->setPassword($password, $userKeyCloak->id);
            }
        }catch (Exception $e) {
            return $e;
        }

    }

}
