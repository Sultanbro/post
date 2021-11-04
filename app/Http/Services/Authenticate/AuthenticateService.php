<?php
namespace App\Http\Services\Authenticate;

use App\Models\UserToken;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\DeclareDeclare;

class AuthenticateService {
    protected $urlAuth = 'http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/token';
    protected $urlInfo = 'http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/userinfo';
    protected $headers = [
        'content-type' => 'application/x-www-form-urlencoded',
        'Accept' => 'application/json',
    ];

    public function getUserInfo($token)
    {
        $headers = [
            'content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token,
        ];
        //  dd($token);
        $response=Http::asForm()->withHeaders($headers)->post($this->urlInfo);
        // dd($response);
        if (isset($response['error'])){
            return false;
        }
        return $response;
    }


    public function getToken($email, $password)
    {

        $params = ['username' => $email,
            'password' => $password,
            'grant_type' => 'password',
            'client_id' => 'rest-client',];

        $response=Http::asForm()->withHeaders($this->headers)->post($this->urlAuth, $params);

        if (isset($response['error'])){
            return false;
        }
        return $response;
    }
    public function refreshToken($refresh_token)
    {
        $params = [
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
            'client_id' => 'rest-client',
        ];
        $response = Http::asForm()->withHeaders($this->headers)->post($this->urlAuth, $params);

        if (isset($response['error'])) {
            return $response;
        }
        $UserToken = UserToken::where('refresh_token', $refresh_token)->update(['access_token' => $response['access_token'], 'refresh_token' => $response['refresh_token'], 'role_id' => 1]);
        return $UserToken;
    }
}
