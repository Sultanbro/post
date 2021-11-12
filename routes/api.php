<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Post\CommentController;
use App\Http\Controllers\Api\Post\PostController;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('user', function (Request $request) {
    return  Auth()->user();
});

//Route::middleware('auth.bearer')->get('/user', function (Request $request) {
//    $userId = UserToken::where('access_token', $request->token)->first();
//    dd(\Illuminate\Support\Facades\Auth::user());
//    return $userId;
//    $user = User::where('id', $userId['user_id'])->first();
//    return $user;
//});

Route::post('/register', [RegisterController::class, 'index'])->withoutMiddleware('auth.bearer');
Route::post('/login', [LoginController::class, 'index'])->withoutMiddleware('auth.bearer');
Route::post('/logout', [LogoutController::class, 'index'])->middleware('auth.bearer');
Route::resource('posts', PostController::class);
Route::resource('comments', CommentController::class);

