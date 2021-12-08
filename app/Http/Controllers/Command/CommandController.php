<?php

namespace App\Http\Controllers\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class CommandController extends Controller
{
    public function command(Request $request)
    {
        return Auth::id();
        if (Auth::id() == 2) {
            if ($request->command == 'migrate') {
                return Artisan::call('migrate');
            }
            if ($request->command == 'rollback') {
                return Artisan::call('migrate:rollback', ['--step' => $request->match, '--step' => true ]);
            }
        }
    }
}
