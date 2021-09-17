<?php

namespace App\Console;

use App\Console\Commands\DiningTable;
use App\Console\Commands\ModelMakeCommand;
use App\Console\Commands\OverdueActivityApply;
use App\Console\Commands\OverdueOrder;
use App\Console\Commands\OverUseActivityApply;
use App\Console\Commands\OverUseOrder;
use App\Console\Commands\SendSms;
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
        ModelMakeCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
