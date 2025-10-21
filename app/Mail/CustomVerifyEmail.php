<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Mailable
{
    use SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        // Buat signed URL tanpa expired
        $url = URL::signedRoute('verification.verify', [
            'id' => $this->user->id,
            'hash' => sha1($this->user->email),
        ]);

        return $this->subject('Verifikasi Akun Anda')
            ->view('emails.verify')
            ->with([
                'name' => $this->user->name,
                'verifyUrl' => $url,
            ]);
    }
}
