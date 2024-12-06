<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SendVoucherReminderJob;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Call the controller method directly
            app(\App\Http\Controllers\ReminderController::class)->sendVoucherReminders();
        })->dailyAt('06:00');

        $schedule->call(function () {
            // Call the controller method directly
            app(\App\Http\Controllers\ReminderController::class)->updateExpiredVoucher();
        })->dailyAt('00:05');
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
