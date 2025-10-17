<?php

namespace App\Mail;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends BaseVerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }

    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Akun Anda di ' . config('app.name'))
            ->greeting('Halo ' . $notifiable->name . ' 👋')
            ->line('Terima kasih telah mendaftar di ' . config('app.name') . '.')
            ->line('Klik tombol di bawah ini untuk memverifikasi email Anda.')
            ->action('Verifikasi Sekarang', $url)
            ->line('Jika Anda tidak merasa mendaftar, abaikan pesan ini.');
    }
}
