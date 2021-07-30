<?php

namespace App\Console;

use App\Tasks\AdmitCases;
use App\Tasks\ClearOverdue;
use App\Tasks\DischargeCases;
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
        $schedule->call(new DischargeCases)->hourlyAt(49);
        $schedule->call(new AdmitCases)->hourlyAt(3);
        $schedule->call(new AdmitCases)->hourlyAt(33);
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
