<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pricing;

class RenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pricing;
    public $daysRemaining;

    public function __construct(Pricing $pricing, $daysRemaining)
    {
        $this->pricing = $pricing;
        $this->daysRemaining = $daysRemaining;
    }

    public function build()
    {
        return $this->subject('⚠ Pengingat Perpanjangan Langganan Anda')
            ->view('emails.renewal_reminder');
    }
}
