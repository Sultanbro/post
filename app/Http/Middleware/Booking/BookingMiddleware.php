<?php

namespace App\Http\Middleware\Booking;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getActionMethod() === 'index')if (!Gate::allows('index_booking'))abort(403);
        if ($request->route()->getActionMethod() === 'show')if (!Gate::allows('show_booking'))abort(403);
        if ($request->route()->getActionMethod() === 'update')if (!Gate::allows('update_booking'))abort(403);
        if ($request->route()->getActionMethod() === 'store')if (!Gate::allows('create_booking'))abort(403);
        if ($request->route()->getActionMethod() === 'destroy')if (!Gate::allows('delete_booking'))abort(403);

        return $next($request);
    }
}
