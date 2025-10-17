@component('mail::message')
    # Verifikasi Akun Anda

    Halo {{ $user->name ?? 'Pengguna' }},

    Terima kasih telah mendaftar di **{{ config('app.name') }}**!
    Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini.

    @component('mail::button', ['url' => $actionUrl])
        Verifikasi Email Saya
    @endcomponent

    Jika Anda tidak membuat akun ini, abaikan pesan ini.

    Salam hangat,
    **Tim {{ config('app.name') }}**
@endcomponent
