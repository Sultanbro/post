<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\Auth\LoginController;
use App\Models\UserToken;
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
        $this->checkToken();
        return $next($request);
    }

    private function checkToken()
    {

        $login = new LoginController();

        $checkToken = $login->getInfo($this->token);

        if (!$checkToken) {
            $user_info = UserToken::where('access_token', $this->token)->first();
            $result = $login->refreshToken($user_info->refresh_token);
            if (!$result) {
                return response()->json(['error' => 'Authorization'], 401);
            }
        }

    }
}

