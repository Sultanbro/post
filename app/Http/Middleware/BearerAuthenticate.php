<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

    private function checkToken() : void
    {

        // 1 send post request http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/userinfo
        // Header Authorization AccessToken

        //if error refresh

        //  after refresh error throw exception

        // ok
    }
}
