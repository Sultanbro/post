<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Services\Authenticate\AuthenticateService;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Http\Services\Authenticate\UserAuthServiceInterface;
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
     * @var UserAuthServiceInterface
     */
    private $userAuth;

    /**
     * LoginController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param KeyCloakServiceInterface $keyCloakService
     * @param UserTokenRepositoryInterface $userTokenRepository
     * @param UserAuthServiceInterface $authService
     */
    public function __construct(UserRepositoryInterface $userRepository, KeyCloakServiceInterface $keyCloakService, UserTokenRepositoryInterface $userTokenRepository, UserAuthServiceInterface $authService)
    {
        $this->userTokenRepository = $userTokenRepository;
        $this->keyCloakService = $keyCloakService;
        $this->userRepository = $userRepository;
        $this->userAuth = $authService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $this->userRepository->userFromEmail($request->email);

        if($request->email == env('TEST_USER')) {
            return $this->userAuth->saveUserToken($user, ['access_token' => env('TEST_TOKEN'), 'refresh_token' => env('TEST_REFRESH_TOKEN')]);
        }

        if (!$user) {
            return response()->json([
                'message' => 'Вы не можете зайти с этими учетными данными',
                'errors' => 'Неавторизованный'
            ], 401);
        }

        $token = $this->keyCloakService->getToken($request->email, $request->password);

        if ($token) {
           return $this->userAuth->saveUserToken($user, $token);
        }

        return response()->json([
            'error' => 'no auth for keycloak',
        ], 403);
    }
}
