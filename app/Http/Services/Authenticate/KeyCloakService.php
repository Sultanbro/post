<?php

namespace App\Http\Services\Authenticate;

use App\Models\UserToken;
use App\Repository\UserTokenRepositoryInterface;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\DeclareDeclare;

class KeyCloakService implements KeyCloakServiceInterface
{

    protected $urlAuth = 'http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/token';
    protected $urlInfo = 'http://192.168.30.11:8022/auth/realms/MyCent/protocol/openid-connect/userinfo';
    protected $headers = [
        'content-type' => 'application/x-www-form-urlencoded',
        'Accept' => 'application/json',
    ];
    /**
     * @var UserTokenRepositoryInterface
     */
    private $userTokenRepository;

    /**
     * KeyCloakService constructor.
     * @param UserTokenRepositoryInterface $userTokenRepository
     */
    public function __construct(UserTokenRepositoryInterface $userTokenRepository)
    {
        $this->userTokenRepository = $userTokenRepository;
    }

    /**
     * @param $token
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getUserInfo($token)
    {
        if ($token == env('TEST_TOKEN')) return true;
        if ($token == env('1C_TOKEN')) return '1c';
        $headers = [
            'content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token,
        ];
        $response=Http::asForm()->withHeaders($headers)->post($this->urlInfo);
        if (isset($response['error'])){
            return false;
        }
        return $response;
    }

    /**
     * @param $email
     * @param $password
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
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

    /**
     * @param $refresh_token
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
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
        $UserToken = $this->userTokenRepository->findFromUserToken($refresh_token)->update(['access_token' => $response['access_token'], 'refresh_token' => $response['refresh_token'], 'role_id' => 1]);
        return $UserToken;
    }
}
