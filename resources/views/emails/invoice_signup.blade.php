<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice Pendaftaran Paket</title>
</head>

<body style="font-family: Arial, sans-serif">

    <h2>Invoice Pendaftaran Paket</h2>

    <p>Halo <strong>{{ $user->name }}</strong>,</p>

    <p>Terima kasih telah mendaftar. Berikut detail invoice Anda:</p>

    <table cellpadding="6" cellspacing="0" width="100%" border="1">
        <tr>
            <td>Paket</td>
            <td>{{ $package->name }}</td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{{ $days }} Hari</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>Menunggu Pembayaran</td>
        </tr>
        <tr>
            <td><strong>Total Bayar</strong></td>
            <td>
                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <br>

    <p>
        Silakan lakukan pembayaran dan upload bukti transfer melalui sistem.
    </p>

    <p>Terima kasih</p>

</body>

</html>
