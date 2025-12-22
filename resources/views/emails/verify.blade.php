<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: #f6f9fc;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: #ffffff;
            border-radius: 10px;
            padding: 25px 30px;
            max-width: 520px;
            margin: auto;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        p {
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }

        .link-alt {
            font-size: 13px;
            color: #555;
            margin-top: 15px;
            word-break: break-all;
        }

        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Halo {{ $name ?? 'Pengguna' }},</h2>

        <p>Terima kasih telah mendaftar di <strong>POS Dataprima</strong>.</p>
        <p>Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:</p>

        <p style="text-align:center;">
            <a href="{{ $verifyUrl }}" class="btn">✅ Verifikasi Sekarang</a>
        </p>

        <p class="link-alt">
            Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
            <a href="{{ $verifyUrl }}">{{ $verifyUrl }}</a>
        </p>

        <p class="footer">
            Jika Anda tidak merasa mendaftar di POS Dataprima, abaikan email ini.
        </p>
    </div>
</body>

</html>
