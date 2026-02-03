<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice Renewal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .invoice-box {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-box th,
        .invoice-box td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .invoice-box th {
            background: #f5f5f5;
            text-align: left;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">INVOICE PEMBAYARAN</div>
        <p>
            No Invoice : <strong>INV-RNW-{{ $renewal->id }}</strong><br>
            Tanggal : {{ now()->format('d M Y') }}<br>
            Jatuh Tempo: {{ optional($renewal->created_at)->addDays(2)->format('d M Y') }}
        </p>
    </div>

    <p>
        <strong>Ditagihkan kepada:</strong><br>
        {{ $user->name }}<br>
        {{ $user->email }}
    </p>

    <table class="invoice-box">
        <tr>
            <th>Deskripsi</th>
            <th>Detail</th>
        </tr>
        <tr>
            <td>Paket Lama</td>
            <td>{{ $pricing->namapaket }}</td>
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
            <td class="total">Total Bayar</td>
            <td class="total">
                Rp {{ number_format($renewal->total_price, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>Status:</strong> Menunggu Pembayaran</p>

        <p><strong>Informasi Pembayaran:</strong><br>
            Bank BCA<br>
            No Rekening: 1234567890<br>
            A/N: PT Contoh Aplikasi
        </p>

        <p>
            Silakan lakukan pembayaran sesuai nominal di atas dan unggah
            bukti transfer melalui sistem.
        </p>

        <p>Terima kasih atas kepercayaan Anda</p>
    </div>

</body>

</html>
