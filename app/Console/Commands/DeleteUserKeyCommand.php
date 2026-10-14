<?php

namespace App\Console\Commands;

use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DeleteUserKeyCommand extends Command
{
    protected $urlAuth;
    protected $urlInfo;
    protected $urlRegister;
    protected $keyCloakMasterEmail;
    protected $keyCloakMasterPassword;
    protected $headers = [
        'content-type' => 'application/x-www-form-urlencoded',
        'Accept' => 'application/json',
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dellKey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->urlAuth = config('keycloak.token_url');
        $this->urlInfo = config('keycloak.userinfo_url');
        $this->urlRegister = config('keycloak.users_url');
        $this->keyCloakMasterEmail = config('keycloak.admin_username');
        $this->keyCloakMasterPassword = config('keycloak.admin_password');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        return 'return';
        $params = ['username' => $this->keyCloakMasterEmail,
            'password' => $this->keyCloakMasterPassword,
            'grant_type' => 'password',
            'client_id' => config('keycloak.client_id'),
            'client_secret' => config('keycloak.client_secret'),];

        $response=Http::asForm()->withHeaders($this->headers)->post($this->urlAuth, $params);


        $headers = [
            'content-type' => 'application/json',
            'Accept' => '*/*',
            'Authorization' => 'Bearer '. $response['access_token'],
        ];

        $result = Http::withHeaders($headers)->get($this->urlRegister);
        $data = json_decode($result->body());
        foreach ($data as $user) {
            if ($user->username == $this->keyCloakMasterEmail) {
                continue;
                }else{
                $result = Http::withHeaders($headers)->delete($this->urlRegister . "/" . $user->id);
                $user_unset[] = ['status' => $result->status(), 'user' => $user->username];
            }
        }

        dd($user_unset);
    }
}
