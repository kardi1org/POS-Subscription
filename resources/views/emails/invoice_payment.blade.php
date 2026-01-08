<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice Pembayaran</title>
</head>

<body style="font-family: Arial, sans-serif">

    <h2>Invoice Pembayaran Paket</h2>

    <p>Halo <strong>{{ $user->name }}</strong>,</p>

    <p>Berikut detail invoice pembayaran paket Anda:</p>

    <table cellpadding="6" cellspacing="0" width="100%" border="1">
        <tr>
            <td>Paket Lama</td>
            <td>{{ optional($renewal->oldPackage)->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Paket Baru</td>
            <td>{{ $package->name }}</td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{{ $renewal->duration * 30 }} Hari</td>
        </tr>
        <tr>
            <td>Masa Aktif Sampai</td>
            <td>{{ \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>Menunggu pembayaran</td>
        </tr>
        <tr>
            <td><strong>Total Bayar</strong></td>
            <td><strong>Rp {{ number_format($renewal->total_price, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <br>

    <p>
        Silakan lakukan pembayaran dan upload bukti transfer melalui sistem.
    </p>

    <p>Terima kasih</p>

</body>

</html>
