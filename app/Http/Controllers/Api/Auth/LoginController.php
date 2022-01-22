<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Services\Authenticate\AuthenticateService;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Http\Services\Authenticate\UserAuthServiceInterface;
use App\Models\User;
use App\Models\UserToken;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\User\UserTokenRepositoryInterface;
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
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->userAuth->login($request->email, $request->password);
    }
}
