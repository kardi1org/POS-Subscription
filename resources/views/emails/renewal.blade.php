<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perpanjangan Berhasil</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            background: #fff;
            margin: 30px auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        h2 {
            color: #007bff;
            text-align: center;
        }

        .highlight {
            background: #e8f3ff;
            padding: 10px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .footer {
            font-size: 13px;
            color: #777;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Perpanjangan Masa Aktif Berhasil 🎉</h2>

        <p>Halo <strong>{{ $renewal->pricing->user->name ?? 'Pengguna' }}</strong>,</p>

        <p>
            Terima kasih telah memperpanjang langganan Anda.
            Paket <strong>{{ $renewal->package->name }}</strong> kini aktif kembali untuk
            <strong>{{ $months }}</strong> bulan ke depan.
        </p>

        <div class="highlight">
            <p><strong>Masa aktif baru:</strong></p>
            {{-- <p>Mulai: {{ $pricing->start_date->format('d M Y') }}</p> --}}
            <p>Berakhir: {{ $renewal->pricing->end_date->format('d M Y') }}</p>
        </div>

        <p>
            Kami senang Anda terus mempercayai layanan kami.
            Jika ada pertanyaan atau kendala, jangan ragu untuk menghubungi tim admin kami.
        </p>

        <p style="text-align: center;">
            <a href="{{ url('/') }}" class="button">Kunjungi Website</a>
        </p>

        <div class="footer">
            Salam hangat,<br>
            <strong>Tim Admin</strong>
        </div>
    </div>
</body>

</html>
