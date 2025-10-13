<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusAktifMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pricing;

    public function __construct($pricing)
    {
        $this->pricing = $pricing;
    }

    public function build()
    {
        return $this->subject('Paket Anda Telah Aktif')
            ->view('emails.status_aktif');
    }
}
