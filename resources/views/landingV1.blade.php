<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ config('app.name', 'POS-Subscription') }} - Sistem Kasir Modern untuk Bisnis Anda</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#30e86e",
                        "primary-dark": "#22c55e",
                        "background-light": "#f8fcf9",
                        "background-dark": "#112116",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2e22",
                        "text-main": "#0e1b12",
                        "text-muted": "#526356",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                    },
                    borderRadius: { "DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-text-main antialiased overflow-x-hidden">
    <header
        class="sticky top-0 z-50 w-full border-b border-[#e7f3eb] bg-white/80 backdrop-blur-md transition-all dark:bg-background-dark/80 dark:border-white/10">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2">
                <div class="flex size-8 items-center justify-center rounded-lg bg-primary/20 text-primary-dark">
                    <span class="material-symbols-outlined">point_of_sale</span>
                </div>
                <span
                    class="text-xl font-bold tracking-tight text-text-main dark:text-white">{{ config('app.name', 'POS-Subscription') }}</span>
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a class="text-sm font-medium text-text-muted hover:text-primary-dark transition-colors dark:text-gray-300 dark:hover:text-primary"
                    href="#fitur">Fitur</a>
                <a class="text-sm font-medium text-text-muted hover:text-primary-dark transition-colors dark:text-gray-300 dark:hover:text-primary"
                    href="#harga">Harga</a>
                <a class="text-sm font-medium text-text-muted hover:text-primary-dark transition-colors dark:text-gray-300 dark:hover:text-primary"
                    href="#faq">Bantuan</a>
            </nav>
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}"
                        class="hidden sm:flex h-9 items-center justify-center rounded-lg px-4 text-sm font-semibold text-text-main transition-colors hover:bg-gray-100 dark:text-white dark:hover:bg-white/10">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="h-9 flex items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-text-main shadow-sm transition-all hover:bg-primary-dark focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Daftar
                    </a>
                @else
                    <a href="{{ route('home') }}"
                        class="h-9 flex items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-text-main shadow-sm transition-all hover:bg-primary-dark focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </header>

    <section class="relative overflow-hidden py-16 sm:py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                <div class="flex flex-col gap-6 text-center lg:text-left">
                    <h1
                        class="text-4xl font-black leading-tight tracking-tight text-text-main sm:text-5xl lg:text-6xl dark:text-white">
                        Tinggalkan Rekap Manual. Fokus <span class="text-primary-dark dark:text-primary">Kembangkan
                            Bisnis</span> Anda!
                    </h1>
                    <p class="mx-auto lg:mx-0 max-w-xl text-lg text-text-muted dark:text-gray-300">
                        Solusi kasir digital lengkap untuk UMKM hingga ritel modern. Kelola stok, pantau penjualan, dan
                        terima pembayaran digital dalam satu aplikasi terintegrasi.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="flex h-12 items-center justify-center rounded-lg bg-primary px-6 text-base font-bold text-text-main shadow-lg shadow-primary/20 transition-all hover:bg-primary-dark hover:-translate-y-0.5">
                            Mulai Langganan
                        </a>
                        <a href="#harga"
                            class="flex h-12 items-center justify-center rounded-lg border border-gray-200 bg-white px-6 text-base font-bold text-text-main hover:bg-gray-50 dark:bg-white/10 dark:border-white/10 dark:text-white dark:hover:bg-white/20">
                            Lihat Pilihan Paket
                        </a>
                    </div>
                    <div
                        class="mt-4 flex items-center justify-center lg:justify-start gap-4 text-sm text-text-muted dark:text-gray-400">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
                            <span>Tanpa Kartu Kredit</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
                            <span>Setup Instant</span>
                        </div>
                    </div>
                </div>
                <div class="relative lg:h-auto w-full flex justify-center lg:justify-end">
                    <div
                        class="absolute -right-20 -top-20 h-[300px] w-[300px] rounded-full bg-primary/20 blur-3xl dark:bg-primary/10">
                    </div>
                    <div
                        class="absolute left-0 bottom-0 h-[200px] w-[200px] rounded-full bg-blue-500/10 blur-3xl dark:bg-blue-500/5">
                    </div>
                    <div class="relative z-10 w-full max-w-[650px]">
                        <div class="absolute -left-4 bottom-12 z-20 hidden sm:flex items-center gap-3 rounded-xl bg-white p-4 shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 dark:bg-surface-dark dark:border-white/5 animate-bounce"
                            style="animation-duration: 5s;">
                            <div
                                class="flex size-10 items-center justify-center rounded-full bg-green-100 text-green-600">
                                <span class="material-symbols-outlined">payments</span>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-text-muted dark:text-gray-400">Total Penjualan
                                </div>
                                <div class="text-sm font-bold text-text-main dark:text-white">Rp 12.500.000</div>
                            </div>
                        </div>
                        <div class="absolute -right-4 top-12 z-20 hidden sm:flex items-center gap-3 rounded-xl bg-white p-4 shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 dark:bg-surface-dark dark:border-white/5 animate-bounce"
                            style="animation-duration: 6s;">
                            <div
                                class="flex size-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <span class="material-symbols-outlined">trending_up</span>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-text-muted dark:text-gray-400">Order Baru</div>
                                <div class="text-sm font-bold text-text-main dark:text-white">+18 Transaksi</div>
                            </div>
                        </div>
                        <div
                            class="relative rounded-[1.5rem] bg-[#1a1a1a] p-2 shadow-2xl ring-1 ring-white/10 transition-transform duration-500 hover:scale-[1.01]">
                            <div
                                class="absolute top-0 left-1/2 h-4 w-24 -translate-x-1/2 rounded-b-xl bg-[#0f0f0f] z-10">
                            </div>
                            <div
                                class="aspect-[16/10] overflow-hidden rounded-[1rem] bg-gray-100 relative border-4 border-gray-900">
                                <!-- <img alt="Dashboard POS Application Interface displaying colorful charts and menu grid" class="h-full w-full object-cover object-top" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD0zKiMOxGQtV82_yyA57ev-9G7pt3iPrw7-LEdDMLpqF9ToaKDPfuKQJG7x3WLGa9AraU1RHxqGYeEO3GC-5oNrKREOri3TziwRT0FtFtnMK_IkuA5tdDlLCzCjRTfTZykP44XRkal381Y1rW_ihQQ_7NTANSrnLQ2YtEZPdoDTkZCnUmtoGt_T3HixqIgy1TlV-V6IfXr86eVFZ9ROYuzwBpJlpHH6_bcOz8dsEapsci8wXSUc3RAd1AhnCmPQVvzxGdi6vgoogU2"/> -->
                            </div>
                            <div class="absolute -right-[2px] top-24 h-10 w-[2px] rounded-r bg-[#333]"></div>
                            <div class="absolute -right-[2px] top-40 h-16 w-[2px] rounded-r bg-[#333]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="border-y border-gray-200 bg-white py-8 dark:bg-surface-dark dark:border-white/5">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <p class="text-sm font-medium text-gray-500 mb-6 uppercase tracking-wider">Dipercaya oleh 10,000+ Bisnis di
                Indonesia</p>
            <div
                class="flex flex-wrap items-center justify-center gap-8 opacity-60 grayscale transition-all hover:grayscale-0">
                <div class="flex items-center gap-2 font-bold text-xl text-gray-600 dark:text-gray-400"><span
                        class="material-symbols-outlined">coffee</span> KopiSenja</div>
                <div class="flex items-center gap-2 font-bold text-xl text-gray-600 dark:text-gray-400"><span
                        class="material-symbols-outlined">restaurant</span> BurgerBros</div>
                <div class="flex items-center gap-2 font-bold text-xl text-gray-600 dark:text-gray-400"><span
                        class="material-symbols-outlined">checkroom</span> FashionKu</div>
                <div class="flex items-center gap-2 font-bold text-xl text-gray-600 dark:text-gray-400"><span
                        class="material-symbols-outlined">shopping_basket</span> MartDaily</div>
                <div class="flex items-center gap-2 font-bold text-xl text-gray-600 dark:text-gray-400"><span
                        class="material-symbols-outlined">spa</span> GlamSalon</div>
            </div>
        </div>
    </div>
   

    <section class="py-20 bg-white" id="harga">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-text-main mb-4">Solusi untuk Setiap Skala Bisnis</h2>
                <p class="text-text-secondary max-w-2xl mx-auto">Kami memahami bahwa setiap bisnis memiliki kebutuhan
                    unik. Dataprima POS dirancang untuk tumbuh bersama Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="card-hover bg-white border border-surface-border rounded-2xl p-8 flex flex-col transition-all duration-300 shadow-sm hover:shadow-lg"
                    data-aos="fade-up" data-aos-delay="100">
                    <div class="mb-4">
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">UMKM</span>
                    </div>
                    <h3 class="text-2xl font-bold text-text-main mb-2">Essentials</h3>
                    <p class="text-text-secondary text-sm mb-6">Mulai langkah digital pertama bisnis Anda dengan fitur
                        esensial yang mudah digunakan.</p>
                    <div class="flex-1 space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Pencatatan Transaksi Dasar</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Manajemen Produk Simpel</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Laporan Penjualan Harian</span>
                        </div>
                    </div>
                    <div class="mt-auto pt-6 border-t border-surface-border">
                        <p class="text-text-main font-bold mb-4">Mulai dengan Penawaran Terbaik</p>
                         <a href="{{ route('pricing.create', 1) }}" 
                            class="inline-block w-full text-center bg-transparent border border-surface-border hover:bg-primary hover:border-primary text-text-main hover:text-white font-bold py-3 rounded-lg transition-all duration-300">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                
                <div
                    class="card-hover bg-white border-2 border-primary/20 rounded-2xl p-8 flex flex-col relative overflow-hidden transition-all duration-300 transform md:-translate-y-4 shadow-xl shadow-green-900/5"
                    data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                        Populer
                    </div>
                    <div class="mb-4">
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Cafe / Resto
                            / Retail</span>
                    </div>
                    <h3 class="text-2xl font-bold text-text-main mb-2">Integrated</h3>
                    <p class="text-text-secondary text-sm mb-6">Optimalkan operasional harian dengan sistem manajemen
                        stok dan dapur yang terintegrasi.</p>
                    <div class="flex-1 space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Manajemen Stok Real-time</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Kitchen Display System (KDS)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Manajemen Meja &amp; Order</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Integrasi E-Wallet &amp; QRIS</span>
                        </div>
                    </div>
                    <div class="mt-auto pt-6 border-t border-surface-border">
                        <p class="text-text-main font-bold mb-4">Konsultasikan Kebutuhan Anda</p>
                        <a href="{{ route('pricing.create', 2) }}" 
                        class="block w-full text-center bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-lg transition-all duration-300 shadow-lg shadow-primary/30">
                            Pesan Sekarang
                        </a>
                      </div>
                </div>

                <div
                    class="card-hover bg-white border border-surface-border rounded-2xl p-8 flex flex-col transition-all duration-300 shadow-sm hover:shadow-lg"
                    data-aos="fade-up" data-aos-delay="300">
                    <div class="mb-4">
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Skala
                            Besar</span>
                    </div>
                    <h3 class="text-2xl font-bold text-text-main mb-2">Enterprise</h3>
                    <p class="text-text-secondary text-sm mb-6">Kontrol penuh untuk bisnis multi-cabang dengan analitik
                        mendalam dan kustomisasi API.</p>
                    <div class="flex-1 space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Dashboard Multi-Outlet</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">API Terbuka &amp; Integrasi ERP</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            <span class="text-text-main text-sm">Dedicated Account Manager</span>
                        </div>
                    </div>
                    <div class="mt-auto pt-6 border-t border-surface-border">
                        <p class="text-text-main font-bold mb-4">Dapatkan Solusi Kustom</p>
                        <a href="{{ route('pricing.create', 3) }}" 
                            class="inline-block w-full text-center bg-transparent border border-surface-border hover:bg-primary hover:border-primary text-text-main hover:text-white font-bold py-3 rounded-lg transition-all duration-300">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white dark:bg-surface-dark" id="fitur">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-text-main dark:text-white">Fitur Unggulan</h2>
                <p class="mt-4 text-lg text-text-muted dark:text-gray-400">Dirancang khusus untuk memudahkan operasional
                    harian Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    class="flex flex-col gap-4 p-6 rounded-xl bg-background-light border border-gray-100 hover:border-primary/30 transition-colors dark:bg-background-dark dark:border-white/5">
                    <div class="size-12 rounded-lg bg-primary/20 flex items-center justify-center text-primary-dark">
                        <span class="material-symbols-outlined" style="font-size: 28px;">bolt</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main mb-2 dark:text-white">Transaksi Hitungan Detik</h3>
                        <p class="text-sm text-text-muted leading-relaxed dark:text-gray-400">Proses checkout super
                            cepat, antrian pelanggan jadi lebih lancar bahkan saat jam sibuk.</p>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-4 p-6 rounded-xl bg-background-light border border-gray-100 hover:border-primary/30 transition-colors dark:bg-background-dark dark:border-white/5">
                    <div class="size-12 rounded-lg bg-primary/20 flex items-center justify-center text-primary-dark">
                        <span class="material-symbols-outlined" style="font-size: 28px;">inventory_2</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main mb-2 dark:text-white">Stok Barang Akurat</h3>
                        <p class="text-sm text-text-muted leading-relaxed dark:text-gray-400">Stok otomatis berkurang
                            saat penjualan terjadi. Notifikasi otomatis saat stok menipis.</p>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-4 p-6 rounded-xl bg-background-light border border-gray-100 hover:border-primary/30 transition-colors dark:bg-background-dark dark:border-white/5">
                    <div class="size-12 rounded-lg bg-primary/20 flex items-center justify-center text-primary-dark">
                        <span class="material-symbols-outlined" style="font-size: 28px;">monitoring</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main mb-2 dark:text-white">Pantau dari Mana Saja</h3>
                        <p class="text-sm text-text-muted leading-relaxed dark:text-gray-400">Akses laporan penjualan
                            real-time dari smartphone Anda, kapanpun dan dimanapun.</p>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-4 p-6 rounded-xl bg-background-light border border-gray-100 hover:border-primary/30 transition-colors dark:bg-background-dark dark:border-white/5">
                    <div class="size-12 rounded-lg bg-primary/20 flex items-center justify-center text-primary-dark">
                        <span class="material-symbols-outlined" style="font-size: 28px;">payments</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main mb-2 dark:text-white">Integrasi Pembayaran Digital
                        </h3>
                        <p class="text-sm text-text-muted leading-relaxed dark:text-gray-400">Terima pembayaran via
                            QRIS, E-Wallet (GoPay, OVO, Dana), dan transfer bank langsung.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <div class="relative rounded-2xl bg-primary/10 p-10 sm:p-14 dark:bg-white/5">
                <span
                    class="material-symbols-outlined absolute top-8 left-8 text-6xl text-primary/20">format_quote</span>
                <blockquote class="relative z-10">
                    <p class="text-xl sm:text-2xl font-medium leading-relaxed text-text-main italic dark:text-gray-200">
                        "Sejak pakai {{ config('app.name', 'POS-Subscription') }}, saya nggak perlu lagi pusing ngitung
                        rekap manual tiap malam. Semua otomatis, laporan rapi, dan saya bisa punya lebih banyak waktu
                        untuk fokus ke menu baru."
                    </p>
                </blockquote>
                <div class="mt-8 flex items-center justify-center gap-4">
                    <div class="size-14 overflow-hidden rounded-full bg-gray-300"
                        data-alt="Portrait of Rina smiling in her coffee shop">
                        <img alt="Rina - Pemilik Kedai Kopi Senja" class="h-full w-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrkCMTZszHVi2r0x2WMU86K8T529q5KaVtCdINjmmOpsRLbFICtd7-kd3MqzIfPpTk5AtkWaJWtf5fFax2k26jg_bdI07rXQQ1-C74EF67Jv_1gq85wmxi2J70_FJ1_MIuSEiIkcU8sYowNTsupw9NFupUHxOumf4uVxBw2aFa3g_TL6IRU-PU2osBGO-p6cAZcdSxMOguNxICR0Jxr2PH5RgWc_BgXjFIjvy7cViWCROngUnnwrkOkeU7vUNtw93jin_MXeemnA0L" />
                    </div>
                    <div class="text-left">
                        <div class="font-bold text-text-main dark:text-white">Rina</div>
                        <div class="text-sm text-text-muted dark:text-gray-400">Pemilik "Kedai Kopi Senja"</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-16 bg-white dark:bg-surface-dark" id="faq">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold mb-12 text-text-main dark:text-white">Pertanyaan Umum</h2>
            <div class="space-y-4">
                <div
                    class="rounded-lg border border-gray-200 bg-background-light p-6 dark:bg-background-dark dark:border-white/10">
                    <h3 class="flex items-center gap-3 text-lg font-semibold text-text-main dark:text-white">
                        <span class="material-symbols-outlined text-primary">help</span>
                        Apakah saya perlu membeli hardware khusus?
                    </h3>
                    <p class="mt-3 text-text-muted ml-9 dark:text-gray-400">
                        Tidak wajib. {{ config('app.name', 'POS-Subscription') }} dapat berjalan di tablet Android atau
                        iPad yang mungkin sudah Anda miliki. Namun, kami juga menyediakan paket bundling hardware jika
                        Anda membutuhkan printer thermal dan laci uang.
                    </p>
                </div>
                <div
                    class="rounded-lg border border-gray-200 bg-background-light p-6 dark:bg-background-dark dark:border-white/10">
                    <h3 class="flex items-center gap-3 text-lg font-semibold text-text-main dark:text-white">
                        <span class="material-symbols-outlined text-primary">wifi_off</span>
                        Bagaimana jika internet mati?
                    </h3>
                    <p class="mt-3 text-text-muted ml-9 dark:text-gray-400">
                        Tenang saja! {{ config('app.name', 'POS-Subscription') }} memiliki fitur <strong>Offline
                            Mode</strong>. Anda tetap bisa melakukan transaksi seperti biasa. Data akan otomatis
                        tersinkronisasi ke cloud begitu koneksi internet kembali menyala.
                    </p>
                </div>
                <div
                    class="rounded-lg border border-gray-200 bg-background-light p-6 dark:bg-background-dark dark:border-white/10">
                    <h3 class="flex items-center gap-3 text-lg font-semibold text-text-main dark:text-white">
                        <span class="material-symbols-outlined text-primary">lock</span>
                        Apakah data saya aman?
                    </h3>
                    <p class="mt-3 text-text-muted ml-9 dark:text-gray-400">
                        Sangat aman. Kami menggunakan enkripsi setara bank untuk melindungi data transaksi dan pelanggan
                        Anda. Backup data dilakukan secara otomatis dan berkala ke server cloud yang aman.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="relative overflow-hidden bg-background-dark py-16 px-4 sm:py-24">
        <div
            class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>
        <div class="relative mx-auto max-w-4xl text-center z-10">
            <h2 class="text-3xl font-black tracking-tight text-white sm:text-4xl">
                Siap Membuat Bisnis Anda Lebih Profesional?
            </h2>
            <p class="mt-4 text-lg text-gray-300">
                Bergabunglah dengan ribuan pemilik bisnis sukses lainnya. Mulai langkah digitalisasi Anda hari ini.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="flex h-12 items-center justify-center rounded-lg bg-primary px-8 text-base font-bold text-text-main shadow-lg shadow-primary/20 transition-all hover:bg-primary-dark hover:-translate-y-0.5">
                    Coba {{ config('app.name', 'POS-Subscription') }} Sekarang - Gratis!
                </a>
                <a href="https://wa.me/6281234567890"
                    class="flex h-12 items-center justify-center rounded-lg border border-white/20 bg-white/5 px-8 text-base font-bold text-white hover:bg-white/10 backdrop-blur-sm">
                    Jadwalkan Demo
                </a>
            </div>
        </div>
    </section>
    <footer class="border-t border-gray-200 bg-white py-8 text-center dark:bg-surface-dark dark:border-white/5">
        <div class="mx-auto max-w-7xl px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">point_of_sale</span>
                <span
                    class="font-bold text-text-main dark:text-white">{{ config('app.name', 'POS-Subscription') }}</span>
            </div>
            <p class="text-sm text-text-muted dark:text-gray-500">
                © {{ date('Y') }} {{ config('app.name', 'POS-Subscription') }}. All rights reserved.
            </p>
            <div class="flex gap-6">
                <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span
                        class="sr-only">Instagram</span>IG</a>
                <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span
                        class="sr-only">Twitter</span>TW</a>
                <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span
                        class="sr-only">Facebook</span>FB</a>
            </div>
        </div>
    </footer>
</body>

</html>