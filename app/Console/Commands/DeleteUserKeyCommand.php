<?php

namespace App\Console\Commands;

use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DeleteUserKeyCommand extends Command
{
    protected $urlAuth = 'http://192.168.30.31:8022/auth/realms/MyCent/protocol/openid-connect/token';
    protected $urlInfo = 'http://192.168.30.31:8022/auth/realms/MyCent/protocol/openid-connect/userinfo';
    protected $urlRegister = 'http://192.168.30.31:8022/auth/admin/realms/MyCent/users';
    protected $keyCloakMasterEmail =  'master@mycent.kz';
    protected $keyCloakMasterPassword = 'MyCent!2@#1';
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
            'client_id' => 'rest-client',
            'client_secret' => '439a7958-24a0-4adb-a76f-064214bc1efa',];

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
                $result = Http::withHeaders($headers)->delete("http://192.168.30.31:8022/auth/admin/realms/MyCent/users/" . $user->id);
                $user_unset[] = ['status' => $result->status(), 'user' => $user->username];
            }
        }

        dd($user_unset);
    }
}
