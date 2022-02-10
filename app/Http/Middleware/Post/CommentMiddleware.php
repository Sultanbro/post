<?php

namespace App\Http\Middleware\Post;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getActionMethod() === 'store')if (!Gate::allows('create_post'))abort(403);
        if ($request->route()->getActionMethod() === 'destroy')if (!Gate::allows('delete_post'))abort(403);

        return $next($request);
    }
}
