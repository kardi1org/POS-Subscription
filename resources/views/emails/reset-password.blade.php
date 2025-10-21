<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: #f6f9fc;
            padding: 20px;
        }

        .container {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            max-width: 520px;
            margin: auto;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }

        .link-alt {
            font-size: 13px;
            color: #555;
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
        <h2>Halo {{ $name ?? 'Pengguna' }}, 👋</h2>

        <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>{{ config('app.name') }}</strong>.</p>
        <p>Silakan klik tombol di bawah ini untuk membuat password baru:</p>

        <p style="text-align:center;">
            <a href="{{ $resetUrl }}" class="btn">🔐 Reset Password</a>
        </p>

        <p class="link-alt">
            Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
        </p>

        <p class="footer">
            Jika Anda tidak meminta reset password, abaikan email ini.
        </p>
    </div>
</body>

</html>
