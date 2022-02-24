<?php

namespace App\Http\Middleware\Booking;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingUsersMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getActionMethod() === 'index')if (!Gate::allows('show_booking_users'))abort(403);
        if ($request->route()->getActionMethod() === 'show')if (!Gate::allows('show_booking_users'))abort(403);
        if ($request->route()->getActionMethod() === 'update')if (!Gate::allows('update_booking_users'))abort(403);
        if ($request->route()->getActionMethod() === 'store')if (!Gate::allows('create_booking_users'))abort(403);
        if ($request->route()->getActionMethod() === 'destroy')if (!Gate::allows('delete_booking_users'))abort(403);

        return $next($request);
    }
}
