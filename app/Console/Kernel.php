<?php

namespace App\Console;

use App\Tasks\AdmitCases;
use App\Tasks\ClearOverdue;
use App\Tasks\DischargeCases;
use App\Tasks\PokeHannah;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(new PokeHannah)->everyMinute();
        $schedule->call(new DischargeCases)->hourlyAt(0);
        $schedule->call(new AdmitCases)->hourlyAt(7);
        $schedule->call(new DischargeCases)->hourlyAt(15);
        $schedule->call(new AdmitCases)->hourlyAt(22);
        $schedule->call(new DischargeCases)->hourlyAt(30);
        $schedule->call(new AdmitCases)->hourlyAt(37);
        $schedule->call(new DischargeCases)->hourlyAt(45);
        $schedule->call(new AdmitCases)->hourlyAt(52);
        $schedule->call(new ClearOverdue)->dailyAt('01:11');
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
