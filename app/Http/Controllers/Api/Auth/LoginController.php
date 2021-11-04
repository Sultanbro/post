<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Services\Authenticate\AuthenticateService;
use App\Models\AccessToken;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class LoginController extends Controller
{
    protected $urlAuth = 'http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/token';
    protected $urlInfo = 'http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/userinfo';
    protected $headers = [
                            'content-type' => 'application/x-www-form-urlencoded',
                            'Accept' => 'application/json',
                         ];
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loginService = new AuthenticateService();
        if (!$user = User::where('email', $request->email)->first()) {
            return response()->json([
                'message' => 'Вы не можете зайти с этими учетными данными',
                'errors' => 'Неавторизованный'
            ], 401);
        }

        $token = $loginService->getToken($request->email, $request->password);

        if ($token) {
            if (!$user_token = UserToken::where('user_id', $user->id)->first()) {
                UserToken::create(['access_token' => $token['access_token'], 'refresh_token' => $token['refresh_token'], 'user_id' => $user->id, 'role_id' => 1]);

                return response()->json([
                    'token_type' => 'Bearer',
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'],
                    'created' => 1,
                    'user_info' => $user,
                ], 200);
            }
            $user_token->update(['access_token' => $token['access_token'], 'refresh_token' => $token['refresh_token'], 'role_id' => 1]);
            return response()->json([
                'token_type' => 'Bearer',
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
                'updated' => 1,
                'user_info' => $user,
            ], 200);

        }

        return response()->json([
            'error' => 'no auth',
        ], 403);
    }
}
