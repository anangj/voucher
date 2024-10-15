<?php

namespace App\Jobs;

use App\Mail\VoucherCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVoucherEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $paketVoucher;
    public $voucherHeader;
    public $voucherDetails;
    public $patient;
    public $payment;
    public $voucherFilePaths;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paketVoucher, $voucherHeader, $voucherDetails, $patient, $payment, $voucherFilePaths)
    {
        $this->paketVoucher = $paketVoucher;
        $this->voucherHeader = $voucherHeader;
        $this->voucherDetails = $voucherDetails;
        $this->patient = $patient;
        $this->payment = $payment;
        $this->voucherFilePaths = $voucherFilePaths;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new VoucherCreatedMail($this->paketVoucher ,$this->voucherHeader, $this->voucherDetails, $this->patient, $this->payment);

        // Attach each voucher PDF file
        foreach ($this->voucherFilePaths as $filePath) {
            $email->attach(storage_path('app/' . $filePath)); // Storage path of each PDF
        }

        // Send the email
        Mail::to($this->patient->email)->send($email);
    }
}
