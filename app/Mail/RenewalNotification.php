<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Renewal;

class RenewalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $renewal;
    public $months;

    public function __construct(Renewal $renewal, $months)
    {
        $this->renewal = $renewal;
        $this->months = $months;
    }

    public function build()
    {
        return $this->subject('Perpanjangan Masa Aktif Berhasil')
            ->markdown('emails.renewal');
    }
}
