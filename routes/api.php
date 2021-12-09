<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Post\CommentController;
use App\Http\Controllers\Api\Post\LikeController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\WriteBase\CityController;
use App\Http\Controllers\Api\WriteBase\ClientBaseController;
use App\Http\Controllers\Api\WriteBase\DictisController;
use App\Http\Controllers\Api\WriteBase\RegionController;
use App\Http\Controllers\Command\CommandController;
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

//Auth route
Route::post('/register', [RegisterController::class, 'index'])->withoutMiddleware('auth.bearer');
Route::post('/login', [LoginController::class, 'index'])->withoutMiddleware('auth.bearer');
Route::post('/logout', [LogoutController::class, 'index'])->middleware('auth.bearer');

//Rest api route
Route::post('/clients/info', [ClientBaseController::class, 'acceptClientInfo']);
Route::post('/eorder/info', [ClientBaseController::class, 'acceptEOrder']);
Route::post('/dictis/info', [DictisController::class, 'acceptDictisInfo']);
Route::post('/cities/info', [CityController::class, 'saveCities']);
Route::post('/regions/info', [RegionController::class, 'saveRegions']);

//Post route
Route::resource('posts', PostController::class);
Route::resource('comments', CommentController::class);
Route::resource('likes', LikeController::class);

//Command route
Route::get('/command/', [CommandController::class, 'command'])->withoutMiddleware('auth.bearer');

//User route
Route::resource('users',UserController::class);


