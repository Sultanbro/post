<?php

namespace App\Console;

use App\Console\Commands\CheckAndSendEmailPasswordCommand;
use App\Console\Commands\SandboxCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        SandboxCommand::class,
        CheckAndSendEmailPasswordCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        try {
            $schedule->command('clientTreeSave')->hourly();
            $schedule->command('checkUserTokenAndSend')
                ->dailyAt('07:00')
                ->timezone('Asia/Almaty');
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
