<?php


namespace App\Http\Controllers\Api\WriteBase;

use Illuminate\Http\Request;

class ClientBaseController
{
    public function __construct()
    {
    }

    public function acceptUserInfo(Request $request)
    {
        return 'good';
    }
}
