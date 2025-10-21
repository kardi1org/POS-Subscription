<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class CustomResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $url;

    public function __construct($user, $token)
    {
        $this->user = $user;

        // URL reset password bawaan Laravel
        $this->url = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));
    }

    public function build()
    {
        return $this->subject('Permintaan Reset Password Akun Anda')
            ->view('emails.reset-password')
            ->with([
                'name' => $this->user->name,
                'resetUrl' => $this->url,
            ]);
    }
}
