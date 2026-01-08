<?php

namespace App\Mail;

use App\Models\Renewal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoicePaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $renewal;
    public $pricing;
    public $package;
    public $user;

    public function __construct(Renewal $renewal)
    {
        $this->renewal = $renewal;
        $this->pricing = $renewal->pricing;
        $this->package = $renewal->package;
        $this->user    = $this->pricing->user;
    }

    public function build()
    {
        return $this->subject('Invoice Pembayaran Paket')
            ->view('emails.invoice_payment');
    }
}
