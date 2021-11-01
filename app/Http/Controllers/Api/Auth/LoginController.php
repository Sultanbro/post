<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\AccessToken;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($user = User::where('email', $request->email)->first()) {
            return response()->json([
                'message' => 'Вы не можете зайти с этими учетными данными',
                'errors' => 'Неавторизованный'
            ], 401);
        }

        $token = $this->getToken($request->email, $request->password);

        if ($token) {
            $response = $this->getInfo($token);
            if (!UserToken::find(['user_id' => $user->id])) {
                UserToken::created(['access_token' => $token->access_token, 'refresh_token' => $token->refresh_token, 'user_id' => $user->id, 'role_id' => 1]);
            }
            UserToken::updated(['access_token' => $token->access_token, 'refresh_token' => $token->refresh_token, 'role_id' => 1]);
        }

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token->access_token,
            'user_info' => $user,
        ], 200);
    }

    public function getInfo($token)
    {
        $url = 'http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/userinfo';

        $response=Http::post($url, [
            'content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token,
        ]);

        if (isset($response->error)){
            return false;
        }
        else{
            return $response;
        }
    }

    public function getToken($email, $password)
    {
        $response = Http::post('http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/token',
            ['username' => $email, 'password' => $password, 'grant_type' => 'password', 'client_id' => 'rest-client', ]);

        if (isset($response->error)){
            return false;
        }
        else{
            return $response;
        }
    }

    public function refreshToken($refresh_token)
    {
        $response = Http::post('http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/token',
            ['refresh_token' => $refresh_token, 'grant_type' => 'refresh_token', 'client_id' => 'rest-client', ]);

        if (isset($response->error)) {
            return false;
        }
        UserToken::updated(['access_token' => $response->access_token, 'refresh_token' => $response->refresh_token, 'role_id' => 1]);
        return $response;
    }
}
