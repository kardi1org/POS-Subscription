<?php

namespace App\Mail;

use App\Models\Renewal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF; // ⬅️ dompdf facade

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
        // ============================
        // GENERATE PDF
        // ============================
        $pdf = PDF::loadView('invoices.invoice_renewal', [
            'renewal' => $this->renewal,
            'pricing' => $this->pricing,
            'package' => $this->package,
            'user'    => $this->user,
        ])->setPaper('A4');

        $invoiceNumber = 'INV-RNW-' . $this->renewal->id;

        return $this->subject('Invoice Pembayaran Paket')
            ->view('emails.invoice_payment')
            ->attachData(
                $pdf->output(),
                $invoiceNumber . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
