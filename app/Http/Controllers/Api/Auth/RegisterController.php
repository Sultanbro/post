<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterFormRequest;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
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
}
