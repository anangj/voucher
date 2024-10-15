<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Storage;

class VoucherSent extends Mailable
{
    use Queueable, SerializesModels;

    public $voucher;
    public $patient;
    public $filePath;
    public $attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $patient, $filePath)
    {
        // $this->voucher = $voucher;
        $this->patient = $patient;
        $this->filePath = $filePath;
    }

//     public function build()
// {
//    return $this->view('emails.voucher-html')
//                ->attachFromStorage('/public/storage/bukti_bayar.pdf', 'bukti_bayar.pdf', [
//                    'mime' => 'application/pdf'
//                ]);
// }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('example@example.com', 'Test Sender'),
            subject: 'Voucher Sent',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.voucher-sent',
            with: [
                // 'voucher' => $this->voucher,
                'patient' => $this->patient,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     * 
     * @return array
     */
    public function attachments()
    {
        
        return [];
    }
}
