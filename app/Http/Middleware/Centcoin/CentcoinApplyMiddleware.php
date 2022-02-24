<?php

namespace App\Http\Middleware\Centcoin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CentcoinApplyMiddleware
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
        if ($request->route()->getActionMethod() === 'index')if (!Gate::allows('show_centcoin_apply'))abort(403);
        if ($request->route()->getActionMethod() === 'show')if (!Gate::allows('show_centcoin_apply'))abort(403);
        if ($request->route()->getActionMethod() === 'update')if (!Gate::allows('update_centcoin_apply'))abort(403);
        if ($request->route()->getActionMethod() === 'store')if (!Gate::allows('create_centcoin_apply'))abort(403);
        if ($request->route()->getActionMethod() === 'destroy')if (!Gate::allows('delete_centcoin_apply'))abort(403);

        return $next($request);
    }
}
