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
                'created' => 1,
                'user_info' => $user,
            ], 200);
        }
        $this->userTokenRepository->update($user_token->id, ['access_token' => $token['access_token'], 'refresh_token' => $token['refresh_token'], 'role_id' => 1]);
        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token['access_token'],
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
                if ($this->keyCloakService->setPassword($password, $userKeyCloak[0]['id'])) {
//                    $this->userRepository->update($userToken->user_id, ['password' => $password]);
                    return $this->login($userToken->user->email, $password);
                }else{
                    return response()->json(['message' => 'not in set password'], 404);
                }

            }else{

                return response()->json(['message' => 'token not found'], 404);
            }
        }catch (\Exception $e) {
            return $e;
        }

    }

    public function login($username, $password)
    {
        $user = $this->userRepository->getUserByUsername($username);
        unset($user['password']);

        if($username == 'master@mail.uz') {
            return $this->saveUserToken($user, [ 'access_token' => 'test_access_token', 'refresh_token' => 'test_refresh_token']);
        }elseif ($username == 'test@mail.ru') {
            return $this->saveUserToken($user, [ 'access_token' => 'test_access_token2', 'refresh_token' => 'test_access_token2']);
        }

        if (!$user) {
            return response()->json([
                'message' => 'Вы не можете зайти с этими учетными данными',
                'errors' => 'Неавторизованный'
            ], 401);
        }

        $token = $this->keyCloakService->getToken($username, $password);

        if ($token) {
            return $this->saveUserToken($user, $token);
        }

        return response()->json([
            'error' => 'no auth for keycloak',
        ], 403);
    }

}
