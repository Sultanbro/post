<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterFormRequest;
use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Http\Services\Authenticate\UserAuthServiceInterface;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(RegisterFormRequest $request)
    {
        $user = app(UserRepositoryInterface::class)->create(array_merge(
            $request->only('name', 'email'),
            ['password' => bcrypt($request->password)]
        ));

        if ($user) {
            return response()->json([
                'message' => 'Вы успешно зарегистрировались.'
            ], 200);
        }
    }

//    public function setPassword(Request $request)
//    {
//        if ($user = app(UserRepositoryInterface::class)->userFromEmail($request->email)) {
//            return response()->json(['message' => app(UserAuthServiceInterface::class)->resetPassword($request->email)], 200);
//        }
//        return response()->json(['message' => 'email is not found'], 404);
//    }

    public function resetPassword(Request $request)
    {
        return app(UserAuthServiceInterface::class)->resetPassword($request->token, $request->password);
    }
}
