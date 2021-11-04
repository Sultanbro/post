<?php

namespace App\Http\Middleware;

use App\Http\Services\Authenticate\AuthenticateService;
use App\Models\User;
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
        $this->token = str_replace('Bearer ', '', $this->token);

        if($this->checkToken()) {
            return $next($request);
        }
        throw new UnauthorizedException();
    }

    private function checkToken()
    {
        // Add interface and inject
        $login = new AuthenticateService();

        $checkToken = $login->getUserInfo($this->token);
        if (!$user_info = UserToken::where('access_token', $this->token)->first()) {
            return response()->json(['error' => 'Some text'], 401);
        }

        if (!$checkToken) {
            $result = $login->refreshToken($user_info['refresh_token']);
            if (isset($result['error'])) {
                return false;
            }
        }

        try {
            Auth::login(\Illuminate\Foundation\Auth\User::where('id', $user_info->user_id)->first());
            return true;
        }catch (\Exception $exception) {
            return false;
        }
    }

}

