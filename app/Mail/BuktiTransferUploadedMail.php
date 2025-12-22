<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BuktiTransferUploadedMail extends Mailable
{
    use SerializesModels;

    public $renewal;
    public $pricing;
    public $type;

    public function __construct($data, $type = 'pricing')
    {
        if ($type === 'renewal') {
            $this->renewal = $data->load('pricing');
        } else {
            $this->pricing = $data->load('user');
        }

        $this->type = $type;
    }

    public function build()
    {
        if ($this->type === 'renewal') {
            $pricing = $this->renewal->pricing;

            return $this->subject('Bukti Transfer Perpanjangan/Upgrade Menunggu Verifikasi')
                ->view('emails.bukti_transfer_uploaded')
                ->with([
                    'userName' => $pricing->email,
                    'packageName' => $this->renewal->package->name,
                    'amount' => number_format($this->renewal->total_price, 0, ',', '.'),
                    'buktiUrl' => asset('storage/' . $this->renewal->bukti_transfer),
                    'dashboardUrl' => url('/admin/pricings'),
                ]);
        }

        return $this->subject('Bukti Transfer Baru Menunggu Verifikasi')
            ->view('emails.bukti_transfer_uploaded')
            ->with([
                'userName' => $this->pricing->user->name,
                'packageName' => $this->pricing->namapaket,
                'amount' => number_format($this->pricing->harga_paket * $this->pricing->durasi ?? 0, 0, ',', '.'),
                'buktiUrl' => asset('storage/' . $this->pricing->bukti_transfer),
                'dashboardUrl' => url('/admin/pricings'),
            ]);
    }
}
