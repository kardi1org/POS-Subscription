<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pricing;

class RenewalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $pricing;
    public $months;

    public function __construct(Pricing $pricing, $months)
    {
        $this->pricing = $pricing;
        $this->months = $months;
    }

    public function build()
    {
        return $this->subject('Perpanjangan Masa Aktif Berhasil')
            ->markdown('emails.renewal');
    }
}
