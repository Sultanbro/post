<?php

namespace App\Http\Middleware;

use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Repository\User\UserTokenRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->token = $request->bearerToken();
        if (!$this->token) {
            return \response()->json(['token' => 'not found'], 401);
        }

        if($this->checkToken()) {
           return $next($request);
        }
        return \response()->json(['token' => 'not found'], 401);
    }

    private function checkToken()
    {
        $user_info = app(UserTokenRepositoryInterface::class)->findFromUserAccessToken($this->token);
        if (is_null($user_info)) {
            return false;
        }
        $login = app(KeyCloakServiceInterface::class);
        if (!$login->getUserInfo($this->token)) {
            return false;
        }

        try {
            return (bool) Auth::loginUsingId($user_info->user_id);
        }catch (\Exception $exception) {
            return false;
        }
    }
}
