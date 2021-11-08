<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Services\Authenticate\AuthenticateService;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Models\AccessToken;
use App\Models\User;
use App\Models\UserToken;
use App\Repository\UserRepositoryInterface;
use App\Repository\UserTokenRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class LoginController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var KeyCloakServiceInterface
     */
    private $keyCloakService;
    /**
     * @var UserTokenRepositoryInterface
     */
    private $userTokenRepository;

    /**
     * LoginController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param KeyCloakServiceInterface $keyCloakService
     * @param UserTokenRepositoryInterface $userTokenRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, KeyCloakServiceInterface $keyCloakService, UserTokenRepositoryInterface $userTokenRepository)
    {
        $this->userTokenRepository = $userTokenRepository;
        $this->keyCloakService = $keyCloakService;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (!$user = $this->userRepository->userFromEmail($request->email)) {
            return response()->json([
                'message' => 'Вы не можете зайти с этими учетными данными',
                'errors' => 'Неавторизованный'
            ], 401);
        }

        $token = $this->keyCloakService->getToken($request->email, $request->password);

        if ($token) {
            if (!$user_token = $this->userTokenRepository->findFromUserId($user->id)) {
                $this->userTokenRepository->create(['access_token' => $token['access_token'], 'refresh_token' => $token['refresh_token'], 'user_id' => $user->id, 'role_id' => 1]);

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

        return response()->json([
            'error' => 'no auth',
        ], 403);
    }
}
