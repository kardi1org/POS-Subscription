<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Paket Aktif</title>
</head>

<body style="font-family: Arial, sans-serif;">
    <h2>Halo, {{ $pricing->email }}</h2>
    <p>
        Kami informasikan bahwa paket <strong>{{ $pricing->namapaket }}</strong> Anda telah <strong>aktif</strong>.
    </p>
    <p>Masa aktif: {{ $pricing->start_date->format('d M Y') }} s/d {{ $pricing->end_date->format('d M Y') }}</p>
    <p>Terima kasih telah bergabung bersama kami 🎉</p>
</body>

</html>
