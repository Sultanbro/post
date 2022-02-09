<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * @param $request
     * @param Closure $next
     * @param $role
     * @param null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if(!auth()->user()->hasRole($role)) {
            return \response()->json(['role' => 'not found'], 404);
        }
        if($permission !== null && !auth()->user()->can($permission)) {
            return \response()->json(['role' => 'not found'], 404);
        }
        return $next($request);
    }
}
