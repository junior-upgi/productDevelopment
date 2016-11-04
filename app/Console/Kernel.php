<?php

namespace App\Console;


use DB;
use Carbon\Carbon;
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
        //\App\Console\Commands\Inspire::class,
        \App\Console\Commands\CheckProcess::class,
        \App\Console\Commands\CheckProduct::class,
        \App\Console\Commands\CheckProject::class,
        \App\Console\Commands\CheckEveryDay::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('overdueList')->dailyAt('06:00');
        $schedule->command('checkProcess')->dailyAt('07:00');
        //$schedule->command('checkProduct')->dailyAt('07:00');
        //$schedule->command('checkProject')->dailyAt('07:00');
        $schedule->command('checkEveryDay')->dailyAt('07:00');
        

        $schedule->command('overdueList')->everyMinute();
        //$schedule->command('checkProcess')->everyMinute();
        //$schedule->command('checkProduct')->everyMinute();
        //$schedule->command('checkProject')->everyMinute();
        //$schedule->command('checkEveryDay')->everyMinute();
    }
    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
