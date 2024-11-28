<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VoucherCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paketVoucher;
    public $voucherHeader;
    public $voucherDetails;
    public $patient;
    public $payment;

    /**
     * Create a new message instance.
     *
     * @param $voucherHeader
     * @param $voucherDetails
     */
    public function __construct($paketVoucher, $voucherHeader, $voucherDetails, $patient, $payment)
    {
        $this->paketVoucher = $paketVoucher;
        $this->voucherHeader = $voucherHeader;
        $this->voucherDetails = $voucherDetails;
        $this->patient = $patient;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.voucher-html')
                    ->with([
                        'paketVoucher' => $this->paketVoucher,
                        'voucherHeader' => $this->voucherHeader,
                        'voucherDetails' => $this->voucherDetails,
                        'patient' => $this->patient,
                        'payment' => $this->payment,
                    ])
                    ->subject('Your Voucher Orders');
    }
}
