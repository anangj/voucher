<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\VoucherHeader;
use Illuminate\Support\Facades\Mail;
use App\Mail\VoucherReminderMail;

class SendVoucherReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Set reminder dates based on current date
        $firstReminderDate = Carbon::now()->addDays(7)->format('Y-m-d');
        $secondReminderDate = Carbon::now()->addDay(1)->format('Y-m-d');

        // Query vouchers expiring in 7 days and haven't been reminded
        $firstReminderVouchers = VoucherHeader::where('expiry_date', $firstReminderDate)
            ->where('reminder_1', false)
            ->get();

        // Query vouchers expiring in 1 day and haven't been reminded
        $secondReminderVouchers = VoucherHeader::where('expiry_date', $secondReminderDate)
            ->where('reminder_2', false)
            ->get();

        // Send reminders for first reminder (7 days before expiry)
        foreach ($firstReminderVouchers as $voucher) {
            $this->sendReminder($voucher, '7 days left until your voucher expires!');
            $voucher->reminder_1 = true;
            $voucher->save();
        }

        // Send reminders for second reminder (1 day before expiry)
        foreach ($secondReminderVouchers as $voucher) {
            $this->sendReminder($voucher, '1 day left until your voucher expires!');
            $voucher->reminder_2 = true;
            $voucher->save();
        }
    }

    public function sendReminder($voucher, $message)
    {
        // Example email logic, adjust as needed
        $userEmail = 'user@example.com'; // Replace with the actual user's email logic
        Mail::to($userEmail)->send(new VoucherReminderMail($voucher, $message));
    }
}
