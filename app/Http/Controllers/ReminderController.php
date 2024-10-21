<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Models\VoucherHeader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VoucherReminderMail;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    public function sendVoucherReminders()
    {
        // Handle reminders in a reusable way
        $this->processReminders(1, Carbon::now()->addMonth(), 'reminder_1');
        $this->processReminders(2, Carbon::now()->addDays(7), 'reminder_2');
        $this->processReminders(3, Carbon::now()->addDay(), 'reminder_3');
    }

    /**
     * Reusable function to process reminders
     */
    private function processReminders($reminderNumber, $date, $reminderField)
    {
        try {
            $dateFormatted = $date->format('Y-m-d');
            
            // Fetch voucher headers that meet the expiry and reminder conditions
            $vouchers = VoucherHeader::with('paketVoucher', 'patient')
                ->where('expiry_date', $dateFormatted)
                ->where($reminderField, false)
                ->get();

            foreach ($vouchers as $voucher) {
                $this->sendReminder($voucher, $voucher); // Send the reminder email

                // Update the reminder and last_reminder_sent_at fields
                $voucher->$reminderField = true;
                $voucher->last_reminder_sent_at = Carbon::now();
                $voucher->save();
            }

        } catch (\Throwable $th) {
            // Log error for debugging
            Log::error("Error processing reminder {$reminderNumber}: " . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Send reminder email to the patient
     */
    private function sendReminder($voucher, $data)
    {
        // Assuming you have a Mailable class set up to send the reminder
        Mail::to($voucher->patient->email)->send(new VoucherReminderMail($voucher, $data));
    }


    public function updateExpiredVoucher()
    {
        try {
            // Get yesterday's date
            $date = Carbon::now()->yesterday()->format('Y-m-d');

            // Retrieve vouchers with expiry date equal to yesterday
            $vouchers = VoucherHeader::where('expiry_date', $date)->get();
            // dd($vouchers);

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
