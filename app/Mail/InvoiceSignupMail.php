<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceSignupMail extends Mailable
{
    use SerializesModels;

    public $pricing;
    public $package;
    public $total;
    public $days;
    public $user;

    public function __construct($pricing, $package, $total, $days)
    {
        $this->pricing = $pricing;
        $this->package = $package;
        $this->total   = $total;
        $this->days    = $days;
        $this->user    = $this->pricing->user;
    }

    public function build()
    {
        return $this->subject('Invoice Pendaftaran Paket')
            ->view('emails.invoice_signup');
    }
}
