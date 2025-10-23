<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pengingat Perpanjangan</title>
</head>

<body style="font-family: Arial, sans-serif; color:#333;">
    <h2>Halo, {{ $pricing->user->name }}</h2>

    <p>
        Masa aktif paket <strong>{{ $pricing->namapaket }}</strong> Anda akan berakhir dalam
        <strong>{{ $daysRemaining }} {{ Str::plural('hari', $daysRemaining) }}</strong>.
    </p>

    <p>Segera lakukan perpanjangan agar layanan Anda tetap aktif.</p>

    <p>
        <a href="{{ url('/') }}"
            style="background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">
            Perpanjang Sekarang
        </a>
    </p>

    <p style="font-size:12px; color:#666;">Terima kasih telah menggunakan layanan kami.</p>
</body>

</html>
