<?php

namespace App\Http\Controllers\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\BufferedOutput;

class CommandController extends Controller
{
    public function command(Request $request)
    {
        if ($request->get('key') !== config('dev.key')) {
            return abort(403);
        }

        $output = new BufferedOutput;

        switch ($request->command) {
            case 'migrate':
                Artisan::call('migrate', ['--force' => true], $output);
                return $output->fetch();

            case 'rollback':
                Artisan::call('migrate:rollback', ['--step' => $request->match, '--step' => true], $output);
                return $output->fetch();
        }
    }
}
