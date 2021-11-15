<?php

namespace App\Http\Middleware;

use App\Http\Services\Authenticate\AuthenticateService;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Models\User;
use App\Models\UserToken;
use App\Repository\UserTokenRepositoryInterface;
use Closure;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\UnauthorizedException;

class BearerAuthenticate
{
    public $token;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (($this->token = $request->header('Authorization', null)) === null)
            throw new UnauthorizedException();
        $this->token = str_replace('Bearer ', '', $this->token);

        if($this->checkToken()) {
           return $next($request );
        }
        throw new UnauthorizedException();
    }

    private function checkToken()
    {
        $login = app(KeyCloakServiceInterface::class);
        $checkToken = $login->getUserInfo($this->token);
        if ($checkToken === '1c') {
            return true;
        }
        $user_info = app(UserTokenRepositoryInterface::class)->findFromUserAccessToken($this->token);
        if (is_null($user_info)) {
            return false;
        }
        if (!$checkToken) {
            $result = $login->refreshToken($user_info['refresh_token']);
            if (isset($result['error'])) {
                return false;
            }
        }

        try {
            Auth::loginUsingId($user_info->user_id);
        }catch (\Exception $exception) {
            return false;
        }
        return true;
    }
}

