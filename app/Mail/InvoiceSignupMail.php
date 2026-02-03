<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // ============================
        // GENERATE PDF INVOICE
        // ============================
        $pdf = Pdf::loadView('invoices.invoice_signup_pdf', [
            'pricing' => $this->pricing,
            'package' => $this->package,
            'total'   => $this->total,
            'days'    => $this->days,
            'user'    => $this->user,
        ]);

        return $this->subject('Invoice Pendaftaran Paket')
            ->view('emails.invoice_signup')
            ->attachData(
                $pdf->output(),
                'invoice-signup-' . $this->pricing->id . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}
