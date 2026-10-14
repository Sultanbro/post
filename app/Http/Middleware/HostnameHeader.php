<?php

namespace App\Http\Middleware;

use Closure;

class HostnameHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        try {
            $response->header('X-Hostname', gethostname());
        } catch (\Exception $exception) {

        }
        //add more headers here
        return $response;
    }
}
