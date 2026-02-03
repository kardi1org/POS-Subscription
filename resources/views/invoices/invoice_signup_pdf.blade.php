<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Pembayaran</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .invoice-meta {
            text-align: right;
            font-size: 11px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .section {
            margin-top: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            font-size: 13px;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <table class="header">
        <tr>
            <td>
                <h2>INVOICE</h2>
                <small>Sistem Langganan POS</small>
            </td>
            <td class="invoice-meta">
                <div>No Invoice: <strong>INV-{{ $pricing->id }}-{{ now()->format('Ymd') }}</strong></div>
                <div>Tanggal: {{ now()->format('d M Y') }}</div>
                <div>Jatuh Tempo: {{ now()->addDays(1)->format('d M Y') }}</div>
                <br>
                <span class="badge">MENUNGGU PEMBAYARAN</span>
            </td>
        </tr>
    </table>

    {{-- DITAGIHKAN KEPADA --}}
    <div class="section">
        <div class="section-title">Ditagihkan Kepada:</div>
        <div>
            <strong>{{ $user->name ?? '-' }}</strong><br>
            {{ $pricing->email }}
        </div>
    </div>

    {{-- DETAIL LAYANAN --}}
    <div class="section">
        <div class="section-title">Detail Layanan</div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th class="text-right">Durasi</th>
                    <th class="text-right">Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Paket {{ $package->name }}<br>
                        <small>Akses sistem POS & fitur sesuai paket</small>
                    </td>
                    <td class="text-right">{{ $days }} Hari</td>
                    <td class="text-right">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>

                <tr class="total-row">
                    <td colspan="2" class="text-right">Total</td>
                    <td class="text-right">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- INFORMASI PEMBAYARAN --}}
    <div class="section">
        <div class="section-title">Informasi Pembayaran</div>

        <table>
            <tr>
                <th width="30%">Bank</th>
                <td>BCA</td>
            </tr>
            <tr>
                <th>No Rekening</th>
                <td>1234567890</td>
            </tr>
            <tr>
                <th>Atas Nama</th>
                <td>PT Contoh Teknologi</td>
            </tr>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <p>
            Silakan lakukan pembayaran sebelum tanggal jatuh tempo.<br>
            Setelah pembayaran, upload bukti transfer melalui sistem.
        </p>

        <p>
            Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.
        </p>
    </div>

</body>

</html>
