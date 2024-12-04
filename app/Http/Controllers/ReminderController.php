<?php

namespace App\Http\Controllers;

use App\Jobs\SendVoucherReminderJob;
use App\Models\VoucherHeader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    public function sendVoucherReminders()
    {
        // Handle reminders in a reusable way
        $this->processReminders(1, Carbon::now()->addMonth()->endOfDay(), 'reminder_1', 'last_reminder_sent_at');
        $this->processReminders(2, Carbon::now()->addDays(7)->endOfDay(), 'reminder_2', 'last_reminder_sent_at');
        $this->processReminders(3, Carbon::now()->addDay()->endOfDay(), 'reminder_3', 'last_reminder_sent_at');
    }

    /**
     * Reusable function to process reminders
     */
    private function processReminders($reminderNumber, $date, $reminderField, $lastReminderAt)
    {
        try {
            
            // Fetch voucher headers that meet the expiry and reminder conditions
            $vouchers = VoucherHeader::with('paketVoucher', 'patient')
                ->where('expiry_date', $date)
                ->where($reminderField, false)
                ->get();

            foreach ($vouchers as $voucher) {
                SendVoucherReminderJob::dispatch($voucher); // send reminder email

                // Update the reminder and last_reminder_sent_at fields
                $voucher->$reminderField = true;
                $voucher->$lastReminderAt = Carbon::now();
                $voucher->save();
            }

        } catch (\Throwable $th) {
            // Log error for debugging
            Log::error("Error processing reminder {$reminderNumber}: " . $th->getMessage());
            throw $th;
        }
    }

    public function updateExpiredVoucher()
    {
        try {
            // Get yesterday's date
            $date = Carbon::now()->yesterday()->format('Y-m-d');

            // Retrieve vouchers with expiry date equal to yesterday
            $vouchers = VoucherHeader::where('expiry_date', $date)->get();

            // Update the status of each voucher to 'expired'
            foreach ($vouchers as $voucher) {
                $voucher->status = 'expired';
                $voucher->save();
            }

            // Log success
            Log::info("Successfully updated expired vouchers for date: {$date}");

        } catch (\Throwable $th) {
            // Log the error for debugging
            Log::error("Error updating expired vouchers: " . $th->getMessage());

            // Optionally rethrow the exception if needed
            throw $th;
        }
    }

}
