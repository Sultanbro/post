<?php

namespace App\Http;

use App\Http\Middleware\BearerAuthenticate;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\HostnameHeader::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
//            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            BearerAuthenticate::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'auth.bearer' => BearerAuthenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,



//        Api controller police
//        Role Controllers
        'users_role.police' => \App\Http\Middleware\Permission\UsersRoleMiddleware::class,
        'role.police' =>\App\Http\Middleware\Permission\RoleMiddleware::class,
//        Booking Controllers
        'booking.police' =>\App\Http\Middleware\Booking\BookingMiddleware::class,
        'booking_user.police' =>\App\Http\Middleware\Booking\BookingUsersMiddleware::class,
        'room.police' =>\App\Http\Middleware\Booking\RoomMiddleware::class,
//        Post Controllers
        'post.police' =>\App\Http\Middleware\Post\PostMiddleware::class,
        'comment.police' =>\App\Http\Middleware\Post\CommentMiddleware::class,
//          Avatar Controller
        'avatar.police' => \App\Http\Middleware\Avatar\AvatarMiddleware::class,
//          Centcoin Controllers
        'centcoin.police' => \App\Http\Middleware\Centcoin\CentcoinMiddleware::class,
        'centcoin_apply.police' => \App\Http\Middleware\Centcoin\CentcoinApplyMiddleware::class,
    ];
}
