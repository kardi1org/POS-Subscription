<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Notifikasi Bukti Transfer</title>
    <style>
        /* Reset sederhana */
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            color: #334155;
        }

        .wrapper {
            max-width: 680px;
            margin: 28px auto;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(90deg, #075985, #0ea5a3);
            color: #fff;
            padding: 18px 22px;
        }

        .card-body {
            padding: 22px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 20px;
            color: #05203a;
        }

        p {
            margin: 10px 0;
            line-height: 1.5;
            color: #475569;
        }

        .meta {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 12px;
            margin: 14px 0;
            color: #0f172a;
        }

        .btn {
            display: inline-block;
            background: #0ea5a3;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn.secondary {
            background: #64748b;
        }

        .muted {
            color: #64748b;
            font-size: 13px;
        }

        .row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .col {
            flex: 1;
            min-width: 180px;
        }

        .small {
            font-size: 13px;
            color: #475569;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            padding: 18px 12px;
        }

        a.link {
            color: #075985;
            text-decoration: underline;
        }

        @media (max-width:520px) {
            .wrapper {
                padding: 12px;
            }

            .card-body {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <h1>Bukti Transfer Baru Menunggu Verifikasi</h1>
            </div>

            <div class="card-body">
                <p>Halo Admin,</p>

                <p>Pengguna <strong>{{ $userName ?? 'Pengguna' }}</strong> baru saja mengunggah bukti transfer untuk
                    paket <strong>{{ $packageName ?? '-' }}</strong>.</p>

                <div class="meta">
                    <div class="row">
                        <div class="col">
                            <div class="small">Total Pembayaran</div>
                            <div style="font-weight:700; margin-top:6px;">Rp {{ $amount ?? '0' }}</div>
                        </div>
                        <div class="col">
                            <div class="small">Waktu Unggah</div>
                            <div style="font-weight:700; margin-top:6px;">
                                {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <p class="small">Silakan periksa bukti pembayaran dan lakukan verifikasi pada sistem. Jika bukti valid,
                    ubah status perpanjangan menjadi <strong>aktif</strong> di dashboard admin.</p>

                <p style="margin-top:18px; text-align:center;">
                    @if (!empty($buktiUrl))
                        <a href="{{ $buktiUrl }}" class="btn" target="_blank" rel="noopener">Lihat Bukti
                            Transfer</a>
                    @endif

                    <a href="{{ $dashboardUrl ?? url('/admin/pricings') }}" class="btn secondary"
                        style="margin-left:12px;">Buka Dashboard Admin</a>
                </p>

                <hr style="border:none;border-top:1px solid #eef2f7;margin:18px 0;">

                <p class="muted">Jika tombol tidak berfungsi, Anda juga dapat membuka link bukti langsung di bawah ini:
                </p>
                <p class="muted"><a class="link" href="{{ $buktiUrl ?? '#' }}" target="_blank"
                        rel="noopener">{{ $buktiUrl ?? '—' }}</a></p>

                <p class="small" style="margin-top:18px;">Terima kasih,<br><strong>Tim
                    </strong></p>
            </div>
        </div>

        <div class="footer">
            Email ini dikirim otomatis oleh sistem. Jangan balas langsung ke email ini.
        </div>
    </div>
</body>

</html>
