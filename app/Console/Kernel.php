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
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            // Call the controller method directly
            app(\App\Http\Controllers\ReminderController::class)->sendVoucherReminders();
        })->everyMinute();

        $schedule->call(function () {
            // Call the controller method directly
            app(\App\Http\Controllers\ReminderController::class)->updateExpiredVoucher();
        })->dailyAt('14:40');

        // $schedule->job(new SendVoucherReminderJob())->everyMinute()
        // ->onSuccess(function() {
        //     Log::info("SendVoucherReminderJob success send message");
        // })
        // ->onFailure(function() {
        //     Log::info('SendVoucherReminderJob cannot send message');
        // });
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
