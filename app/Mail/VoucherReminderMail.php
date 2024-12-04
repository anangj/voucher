<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoucherReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $voucher;
    public $messageContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($voucher)
    {
        $this->voucher = $voucher;
        // $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->view('emails.voucher-reminder')
                    ->subject('Voucher Expiry Reminder')
                    ->with([
                        'data' => $this->voucher,
                    ]);
    }
}
