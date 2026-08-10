<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\PruneNotifications;
use App\Console\Commands\ProcessAccountDeletions;
use App\Console\Commands\SubscriptionCheck;
use App\Console\Commands\trialSubscriptionCheck;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SubscriptionCheck::class,
        trialSubscriptionCheck::class,
        ProcessAccountDeletions::class,
        PruneNotifications::class

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
        $schedule->command('subscription:check')->hourly();
        $schedule->command('trialSubscription:check')->hourly();
        $schedule->command('account:process-deletions')->daily();
        $schedule->command('notifications:prune')->daily();

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
