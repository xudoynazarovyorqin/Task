<?php

namespace App\Console;

use App\Console\Commands\Cms;
use App\Console\Commands\CreateApplicationPartEveryMonth;
use App\Console\Commands\Install;
use App\Console\Commands\SendSmsToTelegramBotAboutTopUpBalance;
use App\Console\Commands\SuperAdmin;
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
        Install::class,
        Cms::class,
        SuperAdmin::class,
        CreateApplicationPartEveryMonth::class,
        SendSmsToTelegramBotAboutTopUpBalance::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('everyday:cleartoken')
                 ->daily();
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
