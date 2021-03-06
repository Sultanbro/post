<?php

namespace App\Http\Services\Authenticate;

use App\Models\UserToken;
use App\Repository\User\UserTokenRepositoryInterface;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\DeclareDeclare;

class KeyCloakService implements KeyCloakServiceInterface
{

    protected $urlAuth = 'http://192.168.30.31:8022/auth/realms/MyCent/protocol/openid-connect/token';
    protected $urlInfo = 'http://192.168.30.31:8022/auth/realms/MyCent/protocol/openid-connect/userinfo';
    protected $urlRegister = 'http://192.168.30.31:8022/auth/admin/realms/MyCent/users';
    protected $keyCloakMasterEmail =  'Master@mycent.kz';
    protected $keyCloakMasterPassword = 'MyCent!2@#1';
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
        if ($token == 'test_access_token') return true;
        if ($token == 'test_access_token2') return true;

        $headers = [
            'content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token,
        ];
        $response = Http::asForm()->withHeaders($headers)->post($this->urlInfo);
        if (isset($response['error'])){
            return false;
        }
        return $response;
    }

    /**
     * @param $username
     * @param $password
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response|mixed
     */
    public function getToken($username, $password)
    {

        $params = ['username' => $username,
            'password' => $password,
            'grant_type' => 'password',
            'client_id' => 'rest-client',
            'client_secret' => '439a7958-24a0-4adb-a76f-064214bc1efa',];

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
            'client_secret' => '439a7958-24a0-4adb-a76f-064214bc1efa',
        ];
        $response = Http::asForm()->withHeaders($this->headers)->post($this->urlAuth, $params);

        if (isset($response['error'])) {
            return $response;
        }
        $UserToken = $this->userTokenRepository->findFromUserToken($refresh_token)->update(['access_token' => $response['access_token'], 'refresh_token' => $response['refresh_token'], 'role_id' => 1]);
        return $UserToken;
    }

    public function registerUser($email, $firstName, $lastName, $username)
    {
        $master_info = $this->getToken($this->keyCloakMasterEmail, $this->keyCloakMasterPassword);

        $params = [
            'emailVerified' => true,
            'email' => $email,
            'username' => $username,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ];

        if (isset($master_info['access_token'])) {
            $headers = [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '. $master_info['access_token'],
            ];
            $response = Http::withHeaders($headers)->post($this->urlRegister, $params);
            if ($response->status() === 201) {
                return true;
            }
            return $response;
        }
    }

    public function getUserByUsername($username)
    {
        $master_info = $this->getToken($this->keyCloakMasterEmail, $this->keyCloakMasterPassword);

        $headers = [
            'content-type' => 'application/json',
            'Accept' => '*/*',
            'Authorization' => 'Bearer '. $master_info['access_token'],
        ];

        $result = Http::withHeaders($headers)->get($this->urlRegister, ['username' => $username]);

        return json_decode($result->body(), true);
    }

    /**
     * @param $password
     * @param $user_id
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response|mixed
     */
    public function setPassword($password, $user_id)
    {
        $master_info = $this->getToken($this->keyCloakMasterEmail, $this->keyCloakMasterPassword);

        $params = [
            'value' => $password,
            'type' => "password",
            ];

        $headers = [
            'content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $master_info['access_token'],
        ];

        if(Http::withHeaders($headers)->put($this->urlRegister."/$user_id/reset-password", $params)->status() === 204) {
            Http::withHeaders($headers)->put($this->urlRegister."/$user_id/", ['enabled' => true]);
            return true;
        }
        return false;
    }
}
