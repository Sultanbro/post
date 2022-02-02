<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Booking\BookingUsersController;
use App\Http\Controllers\Api\Booking\RoomController;
use App\Http\Controllers\Api\Email\EmailController;
use App\Http\Controllers\Api\Post\CommentController;
use App\Http\Controllers\Api\Post\LikeController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\WriteBase\CareerUserController;
use App\Http\Controllers\Api\WriteBase\CityController;
use App\Http\Controllers\Api\WriteBase\ClientBaseController;
use App\Http\Controllers\Api\WriteBase\DictisController;
use App\Http\Controllers\Api\WriteBase\DutyController;
use App\Http\Controllers\Api\WriteBase\RegionController;
use App\Http\Controllers\Api\WriteBase\StaffController;
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


Route::group(['middleware' => 'role:super'], function() {
    Route::get('/dashboard', function() {
        return 'Добро пожаловать,' . auth()->user()->roles();
    });
});


//Auth route
Route::post('/register', [RegisterController::class, 'index'])->withoutMiddleware('auth.bearer');
Route::post('/login', [LoginController::class, 'index'])->withoutMiddleware('auth.bearer');
Route::post('/logout', [LogoutController::class, 'index'])->middleware('auth.bearer');
Route::post('/reset/', [RegisterController::class, 'resetPassword'])->withoutMiddleware('auth.bearer');

//Rest api route
Route::post('/clients/info/accept', [ClientBaseController::class, 'acceptClientInfo']);
Route::post('/eorder/info/accept', [ClientBaseController::class, 'acceptEOrder']);
Route::post('/dictis/info/accept', [DictisController::class, 'acceptDictisInfo']);
Route::post('/cities/info/accept', [CityController::class, 'saveCities']);
Route::post('/regions/info/accept', [RegionController::class, 'saveRegions']);
Route::post('/duty/info/accept', [DutyController::class, 'saveDuties']);
Route::post('/staff/info/accept', [StaffController::class, 'saveStaff']);
Route::post('/career/info/accept', [CareerUserController::class, 'saveCareer']);
Route::post('/avatar', [ClientBaseController::class, 'acceptAvatar']);
Route::post('user/details/accept', [ClientBaseController::class, 'userDetails']);

//Post route
Route::resource('posts', PostController::class);
Route::resource('comments', CommentController::class);
Route::resource('likes', LikeController::class);
Route::get('/filter/posts',[PostController::class, 'getFilter']);

//Command route
Route::get('/command/', [CommandController::class, 'command'])->withoutMiddleware('auth.bearer');

//User route
Route::get('user', [UserController::class, 'getUser']);
Route::resource('user/info',UserController::class)->middleware('auth.bearer');
Route::get('/client/tree', [UserController::class, 'clientTree']);
Route::get('/birthday/', [UserController::class, 'getBDay']);

//Email route
Route::post('send/email/reset/password', [EmailController::class, 'sendResetPasswordEmail']);
Route::post('email/save/file', [EmailController::class, 'saveFile']);
Route::post('email/domain/save', [EmailController::class, 'saveEmailDomain']);

//Booking route
Route::resource('rooms',RoomController::class);
Route::resource('booking/users', BookingUsersController::class);
Route::resource('booking',BookingController::class);

