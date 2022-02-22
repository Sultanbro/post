<?php

namespace App\Http\Middleware\Avatar;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AvatarMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getActionMethod() === 'index')if (!Gate::allows('index_avatar'))abort(403);
        if ($request->route()->getActionMethod() === 'show')if (!Gate::allows('show_avatar'))abort(403);
        if ($request->route()->getActionMethod() === 'update')if (!Gate::allows('update_avatar'))abort(403);
        if ($request->route()->getActionMethod() === 'store')if (!Gate::allows('create_avatar'))abort(403);
        if ($request->route()->getActionMethod() === 'destroy')if (!Gate::allows('delete_avatar'))abort(403);

        return $next($request);
    }
}
