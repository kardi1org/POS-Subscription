<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ config('app.name', 'POS-Subscription') }} - Sistem Kasir Modern untuk Bisnis Anda</title>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#10b981", "primary-dark": "#059669", "primary-light": "#d1fae5", "background-light": "#ffffff",
                        "background-off-white": "#f8fafc", "surface": "#ffffff",
                        "surface-alt": "#f1f5f9", "surface-border": "#e2e8f0", "text-main": "#373737ff", "text-secondary": "#64748b", "text-inverted": "#ffffff",
                    },
                    fontFamily: {
                        "display": ["Mulish", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Back to Top JS logic
            const backToTopBtn = document.getElementById("backToTop");
            
            window.addEventListener("scroll", function () {
                if (window.pageYOffset > 400) {
                    backToTopBtn.classList.add("show");
                } else {
                    backToTopBtn.classList.remove("show");
                }
            });

            backToTopBtn.addEventListener("click", function () {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        });
    </script>
    <style>
        body {
            font-family: 'Mulish', sans-serif;
        }

        .hero-glow {
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            border-color: #10b981;
            box-shadow: 0 10px 40px -10px rgba(16, 185, 129, 0.15);
        }
    </style>

    <style>
        /* Smooth scroll global */
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
            /* Jarak agar tidak tertutup navbar */
        }

        /* Opsional: Animasi masuk saat menu pertama kali muncul (Entrance) */
        .md\:flex a {
            animation: slideInTop 0.5s ease forwards;
            opacity: 0;
        }

        @keyframes slideInTop {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Delay animasi tiap menu agar bergantian */
        .md\:flex a:nth-child(1) {
            animation-delay: 0.1s;
        }

        .md\:flex a:nth-child(2) {
            animation-delay: 0.2s;
        }

        .md\:flex a:nth-child(3) {
            animation-delay: 0.3s;
        }

        .md\:flex a:nth-child(4) {
            animation-delay: 0.4s;
        }

        /* Back to Top Button Styles */
        #backToTop {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(20px);
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
            border: none;
            outline: none;
        }

        #backToTop.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        #backToTop:hover {
            background-color: #059669;
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
        }

        #backToTop span {
            font-size: 28px;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-background-light text-text-main overflow-x-hidden selection:bg-primary selection:text-white">
    <header
        class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-surface-border shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                     <a href="#topsection" class="md:flex items-center gap-8">
                    <div class="flex items-center gap-2">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-primary/20 text-primary-dark">
                            <span class="material-symbols-outlined">point_of_sale</span>
                        </div>
                        <span
                            class="text-xl font-bold tracking-tight text-gray-700 dark:text-white">{{ config('app.name', 'POS-Subscription') }}</span>
                    </div>
                </a>
                </div>
               
                <div class="hidden md:flex items-center gap-8">
                    @php
                        $navItems = [
                            ['id' => 'features', 'label' => 'Fitur'],
                            ['id' => 'solutions', 'label' => 'Solusi'],
                            ['id' => 'vision', 'label' => 'Tentang Kami'],
                            ['id' => 'faq', 'label' => 'FAQ'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        <a class="relative py-2 text-sm font-medium text-text-secondary hover:text-primary transition-all duration-300 group"
                            href="#{{ $item['id'] }}">
                            {{ $item['label'] }}
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    @endforeach
                </div>
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}"
                            class="hidden sm:flex h-9 items-center justify-center rounded-lg px-4 text-sm text-gray-700 font-semibold transition-colors hover:bg-gray-100 dark:text-white dark:hover:bg-white/10">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="h-9 flex items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark focus:ring-2 focus:ring-primary focus:ring-offset-2">
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
        </div>
    </header>

    <section class="relative overflow-hidden py-16 sm:py-24 lg:py-32" id="topsection">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                <div class="flex flex-col gap-6 text-center lg:text-left" data-aos="fade-right">
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
                        <a href="#solutions"
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
                <div class="relative lg:h-auto w-full flex justify-center lg:justify-end" data-aos="fade-left">
                    <div
                        class="absolute -right-20 -top-20 h-[300px] w-[300px] rounded-full bg-primary/20 blur-3xl dark:bg-primary/10">
                    </div>
                    <div
                        class="absolute -left-40 top-20 h-[200px] w-[200px] rounded-full bg-primary/20 blur-3xl dark:bg-primary/10">
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

                        <img alt="Dashboard POS Application Interface displaying colorful charts and menu grid"
                            class="h-full w-full object-cover object-top"
                            src="https://ddh86g38imawj.cloudfront.net/images/login-hero-1.png" />


                        <!-- <div class="relative rounded-[1.5rem] bg-[#1a1a1a] p-2 shadow-2xl ring-1 ring-white/10 transition-transform duration-500 hover:scale-[1.01]">
                        <div class="absolute top-0 left-1/2 h-4 w-24 -translate-x-1/2 rounded-b-xl bg-[#0f0f0f] z-10"></div>
                        <div class="aspect-[16/10] overflow-hidden rounded-[1rem] bg-gray-100 relative border-4 border-gray-900">
                            <img alt="Dashboard POS Application Interface displaying colorful charts and menu grid" class="h-full w-full object-cover object-top" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD0zKiMOxGQtV82_yyA57ev-9G7pt3iPrw7-LEdDMLpqF9ToaKDPfuKQJG7x3WLGa9AraU1RHxqGYeEO3GC-5oNrKREOri3TziwRT0FtFtnMK_IkuA5tdDlLCzCjRTfTZykP44XRkal381Y1rW_ihQQ_7NTANSrnLQ2YtEZPdoDTkZCnUmtoGt_T3HixqIgy1TlV-V6IfXr86eVFZ9ROYuzwBpJlpHH6_bcOz8dsEapsci8wXSUc3RAd1AhnCmPQVvzxGdi6vgoogU2"/>
                        </div>
                        <div class="absolute -right-[2px] top-24 h-10 w-[2px] rounded-r bg-[#333]"></div>
                        <div class="absolute -right-[2px] top-40 h-16 w-[2px] rounded-r bg-[#333]"></div>
                    </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>


     <!-- Harga paket  -->      
     <section class="py-20 sm:py-32" id="solutions">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-text-main sm:text-4xl dark:text-white">Pilihan Paket
                    Fleksibel</h2>
                <p class="mt-4 text-lg text-text-muted dark:text-gray-400">Pilih paket yang sesuai dengan skala bisnis
                    Anda. Upgrade kapan saja seiring pertumbuhan usaha.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3 lg:gap-8 items-start">
                @foreach($packages as $package)
                    @php
                        $isBusiness = strtolower($package->name) == 'business' || $package->id == 2;
                        $isEnterprise = strtolower($package->name) == 'enterprise' || $package->id == 3;
                    @endphp
                    
                    <div
                        class="relative flex flex-col rounded-2xl border {{ $isBusiness ? 'border-2 border-primary scale-105 z-10 shadow-xl' : 'border-gray-200 shadow-sm transition-shadow hover:shadow-lg' }} bg-white p-8 dark:bg-surface-dark {{ $isBusiness ? 'dark:border-primary' : 'dark:border-white/10' }}"
                        data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        
                        @if($isBusiness)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 rounded-full bg-primary px-4 py-1 text-xs font-bold uppercase tracking-wide text-text-main shadow-sm">
                                Terpopuler
                            </div>
                        @endif

                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-text-main dark:text-white uppercase">{{ $package->name }}</h3>
                            <p class="mt-2 flex items-baseline gap-1">
                                @if($isEnterprise && $package->price == 0)
                                    <span class="text-3xl font-black text-text-main dark:text-white">Hubungi Sales</span>
                                @else
                                    <span class="text-4xl font-black text-text-main dark:text-white">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    <span class="text-sm font-medium text-text-muted">/Bulan</span>
                                @endif
                            </p>
                            <p class="mt-4 text-sm text-text-muted">
                                {{ $package->description ?? (strtolower($package->name) == 'starter' ? 'Cocok untuk usaha rintisan yang baru mulai.' : (strtolower($package->name) == 'business' ? 'Paling pas untuk UMKM yang sedang berkembang.' : 'Solusi custom untuk jaringan ritel besar.')) }}
                            </p>
                        </div>

                        <ul class="mb-8 space-y-4 flex-1">
                            @if($package->features)
                                @foreach($package->features as $feature)
                                    <li class="flex items-center gap-3 text-sm {{ $isBusiness ? 'font-medium text-text-main dark:text-white' : 'text-text-main dark:text-gray-300' }}">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 20px;">
                                            {{ $isBusiness ? 'check_circle' : 'check' }}
                                        </span>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <a href="{{ route('pricing.create', $package->id) }}"
                            class="text-center w-full rounded-lg {{ $isBusiness ? 'bg-primary text-text-main hover:bg-primary-dark shadow-md transform hover:-translate-y-0.5' : 'bg-gray-100 text-text-main hover:bg-gray-200 dark:bg-white/10 dark:text-white dark:hover:bg-white/20' }} py-3 text-sm font-bold transition-all transition-colors">
                            {{ strtolower($package->name) == 'starter' ? 'Daftar Gratis' : (strtolower($package->name) == 'enterprise' ? 'Kontak Kami' : 'Pilih Paket') }}
                        </a>
                        
                        @if($isBusiness)
                            <p class="mt-3 text-center text-xs text-primary-dark font-medium">Hemat 20% dengan bayar tahunan</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <section class="py-20 relative bg-background-off-white" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-16 items-center">
                <div class="lg:w-1/2" data-aos="fade-right">
                    <span class="text-primary-dark font-bold tracking-wider text-sm uppercase mb-2 block">Fitur
                        Unggulan</span>
                    <h2 class="text-3xl md:text-5xl font-black text-text-main mb-6 leading-tight">Teknologi Canggih
                        untuk Akselerasi Bisnis.</h2>
                    <p class="text-text-secondary text-lg mb-8">
                        Dirancang dengan fokus pada kecepatan dan akurasi, Dataprima POS memberikan kendali penuh di
                        ujung
                        jari Anda.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div
                            class="flex flex-col gap-2 p-6 rounded-xl border border-surface-border bg-white shadow-sm hover:border-primary/50 transition-all hover:shadow-md">
                            <div
                                class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center mb-2 text-primary">
                                <span class="material-symbols-outlined text-3xl">bolt</span>
                            </div>
                            <h4 class="text-text-main font-bold text-lg">Transaksi Cepat &amp; Akurat</h4>
                            <p class="text-text-secondary text-sm leading-relaxed">Checkout kilat untuk mengurangi
                                antrian dan meningkatkan kepuasan pelanggan.</p>
                        </div>
                        <div
                            class="flex flex-col gap-2 p-6 rounded-xl border border-surface-border bg-white shadow-sm hover:border-primary/50 transition-all hover:shadow-md">
                            <div
                                class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center mb-2 text-primary">
                                <span class="material-symbols-outlined text-3xl">inventory_2</span>
                            </div>
                            <h4 class="text-text-main font-bold text-lg">Manajemen Stok</h4>
                            <p class="text-text-secondary text-sm leading-relaxed">Sinkronisasi stok otomatis antar
                                cabang dan peringatan stok menipis.</p>
                        </div>
                        <div
                            class="flex flex-col gap-2 p-6 rounded-xl border border-surface-border bg-white shadow-sm hover:border-primary/50 transition-all hover:shadow-md">
                            <div
                                class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center mb-2 text-primary">
                                <span class="material-symbols-outlined text-3xl">analytics</span>
                            </div>
                            <h4 class="text-text-main font-bold text-lg">Laporan Real-time</h4>
                            <p class="text-text-secondary text-sm leading-relaxed">Akses data performa bisnis kapan
                                saja, dari mana saja melalui cloud.</p>
                        </div>
                        <div
                            class="flex flex-col gap-2 p-6 rounded-xl border border-surface-border bg-white shadow-sm hover:border-primary/50 transition-all hover:shadow-md">
                            <div
                                class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center mb-2 text-primary">
                                <span class="material-symbols-outlined text-3xl">credit_card</span>
                            </div>
                            <h4 class="text-text-main font-bold text-lg">Pembayaran Fleksibel</h4>
                            <p class="text-text-secondary text-sm leading-relaxed">Terima tunai, kartu, dan QRIS dengan
                                rekonsiliasi otomatis.</p>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 relative" data-aos="fade-left">
                    <div
                        class="absolute -top-10 -right-10 w-64 h-64 bg-primary/10 rounded-full blur-[100px] pointer-events-none">
                    </div>
                    <div
                        class="absolute -bottom-10 -left-10 w-64 h-64 bg-green-300/20 rounded-full blur-[80px] pointer-events-none">
                    </div>
                    <div class="relative rounded-2xl overflow-hidden border border-surface-border shadow-2xl">
                        <div class="bg-gray-100 p-6 min-h-[400px] flex items-center justify-center bg-cover bg-center"
                            data-alt="Customer paying with smartphone at a modern coffee shop counter"
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDbN7WXCAsjsOyY6JAflAmZd7H5_kISN6YdKvHfCITHPNdFCYWLzGGidAQOxabnJ39LvOxMkpuTG4Q03SYGopbmxP2MxgITrbOYolFt55D6yfzfc4Up4L3dDHilIAcQIbfds1vDeFIAB-6v6jLJ5SxjbkBE_B11WbAdemqV0tKtjTsc_rtdttLefHpZw3NWbd_O4f4ui2Du6ugfzOjjC3fpGh-Q4m0k3R5LRZFSTunJG_UmUtcaOAJC5dTKda72eTml0c08ZSktluN5');">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-surface-alt border-y border-surface-border" id="vision">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-text-main mb-12">Mengapa Memilih Dataprima POS?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <div
                    class="relative pl-8 border-l-2 border-surface-border md:border-l-0 md:border-t-2 md:pt-8 md:pl-0 group">
                    <div
                        class="absolute -left-[9px] top-0 md:-top-[9px] md:left-0 size-4 bg-primary rounded-full ring-4 ring-white">
                    </div>
                    <h3 class="text-xl font-bold text-text-main mb-3 group-hover:text-primary transition-colors">
                        Investasi Jangka Panjang</h3>
                    <p class="text-text-secondary leading-relaxed">
                        Kami bukan sekadar aplikasi, tapi partner teknologi yang terus berinovasi agar bisnis Anda
                        selalu relevan di masa depan.
                    </p>
                </div>
                <div
                    class="relative pl-8 border-l-2 border-surface-border md:border-l-0 md:border-t-2 md:pt-8 md:pl-0 group">
                    <div
                        class="absolute -left-[9px] top-0 md:-top-[9px] md:left-0 size-4 bg-primary rounded-full ring-4 ring-white">
                    </div>
                    <h3 class="text-xl font-bold text-text-main mb-3 group-hover:text-primary transition-colors">
                        Keamanan Data Prioritas</h3>
                    <p class="text-text-secondary leading-relaxed">
                        Enkripsi tingkat bank untuk melindungi data transaksi dan pelanggan Anda. Bisnis tenang, fokus
                        berkembang.
                    </p>
                </div>
                <div
                    class="relative pl-8 border-l-2 border-surface-border md:border-l-0 md:border-t-2 md:pt-8 md:pl-0 group">
                    <div
                        class="absolute -left-[9px] top-0 md:-top-[9px] md:left-0 size-4 bg-primary rounded-full ring-4 ring-white">
                    </div>
                    <h3 class="text-xl font-bold text-text-main mb-3 group-hover:text-primary transition-colors">
                        Dukungan Penuh</h3>
                    <p class="text-text-secondary leading-relaxed">
                        Tim support kami siap membantu Anda dari proses onboarding hingga troubleshooting operasional
                        harian.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-white" id="faq">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-text-main text-center mb-10">Pertanyaan Umum</h2>
            <div class="space-y-4">
                <details
                    class="group bg-white border border-surface-border rounded-lg p-6 [&amp;_summary::-webkit-details-marker]:hidden open:shadow-md transition-shadow">
                    <summary class="flex items-center justify-between cursor-pointer">
                        <h3 class="text-lg font-medium text-text-main">Apakah saya perlu membeli hardware khusus?</h3>
                        <span
                            class="material-symbols-outlined text-text-secondary transition group-open:rotate-180">expand_more</span>
                    </summary>
                    <p class="mt-4 text-text-secondary leading-relaxed">
                        Dataprima POS dirancang fleksibel. Anda bisa menggunakan tablet (iPad/Android) atau PC yang
                        sudah
                        Anda miliki. Namun, kami juga menyediakan rekomendasi paket hardware yang teruji untuk performa
                        maksimal.
                    </p>
                </details>
                <details
                    class="group bg-white border border-surface-border rounded-lg p-6 [&amp;_summary::-webkit-details-marker]:hidden open:shadow-md transition-shadow">
                    <summary class="flex items-center justify-between cursor-pointer">
                        <h3 class="text-lg font-medium text-text-main">Bagaimana jika koneksi internet mati?</h3>
                        <span
                            class="material-symbols-outlined text-text-secondary transition group-open:rotate-180">expand_more</span>
                    </summary>
                    <p class="mt-4 text-text-secondary leading-relaxed">
                        Tidak perlu khawatir. Dataprima POS memiliki mode offline yang memungkinkan Anda tetap
                        bertransaksi.
                        Data akan otomatis tersinkronisasi ke cloud begitu koneksi internet kembali normal.
                    </p>
                </details>
                <details
                    class="group bg-white border border-surface-border rounded-lg p-6 [&amp;_summary::-webkit-details-marker]:hidden open:shadow-md transition-shadow">
                    <summary class="flex items-center justify-between cursor-pointer">
                        <h3 class="text-lg font-medium text-text-main">Apakah data bisnis saya aman?</h3>
                        <span
                            class="material-symbols-outlined text-text-secondary transition group-open:rotate-180">expand_more</span>
                    </summary>
                    <p class="mt-4 text-text-secondary leading-relaxed">
                        Keamanan adalah prioritas utama kami. Kami menggunakan enkripsi data standar industri dan
                        melakukan backup berkala secara otomatis untuk memastikan data Anda aman dari kehilangan atau
                        akses yang tidak sah.
                    </p>
                </details>
                <details
                    class="group bg-white border border-surface-border rounded-lg p-6 [&amp;_summary::-webkit-details-marker]:hidden open:shadow-md transition-shadow">
                    <summary class="flex items-center justify-between cursor-pointer">
                        <h3 class="text-lg font-medium text-text-main">Apakah ada biaya tersembunyi?</h3>
                        <span
                            class="material-symbols-outlined text-text-secondary transition group-open:rotate-180">expand_more</span>
                    </summary>
                    <p class="mt-4 text-text-secondary leading-relaxed">
                        Transparansi adalah kunci kemitraan kami. Semua biaya akan dijelaskan di awal sesuai paket yang
                        Anda pilih. Tidak ada biaya setup kejutan atau biaya maintenance yang tidak disepakati.
                    </p>
                </details>
            </div>
        </div>
    </section>
    <section class="py-20 bg-background-off-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="zoom-in">
            <div
                class="relative bg-gradient-to-r from-green-600 to-green-800 rounded-2xl p-10 md:p-16 text-center overflow-hidden shadow-2xl">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;">
                </div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Siap Tingkatkan Efisiensi Bisnis Anda?
                    </h2>
                    <p class="text-green-50 text-lg mb-10 max-w-2xl mx-auto">
                        Bergabunglah dengan bisnis modern yang telah beralih ke solusi cerdas. Jadwalkan demo gratis
                        sekarang untuk melihat bagaimana Dataprima POS bekerja.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <button
                            class="bg-white text-green-700 hover:bg-gray-50 font-bold py-3.5 px-8 rounded-lg shadow-lg transition-colors">
                            Minta Demo Gratis
                        </button>
                        <button
                            class="bg-transparent border border-white text-white hover:bg-white/10 font-bold py-3.5 px-8 rounded-lg transition-colors">
                            Hubungi Kami
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-white border-t border-surface-border pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="size-6 text-primary">
                            <svg class="w-full h-full" fill="none" viewBox="0 0 48 48"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24 4C12.9543 4 4 12.9543 4 24C4 35.0457 12.9543 44 24 44C35.0457 44 44 35.0457 44 24C44 12.9543 35.0457 4 24 4ZM24 10C26.7614 10 29 12.2386 29 15C29 17.7614 26.7614 20 24 20C21.2386 20 19 17.7614 19 15C19 12.2386 21.2386 10 24 10ZM14 36.5657C14 36.5657 16.5 31 24 31C31.5 31 34 36.5657 34 36.5657C31.3093 39.2941 27.8181 40.8354 24 40.8354C20.1819 40.8354 16.6907 39.2941 14 36.5657Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                        <span class="text-text-main font-bold text-lg">Dataprima POS</span>
                    </div>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        Solusi kasir cerdas untuk pertumbuhan bisnis modern. Efisien, aman, dan terpercaya.
                    </p>
                </div>
                <div>
                    <h4 class="text-text-main font-bold mb-4">Produk</h4>
                    <ul class="space-y-2">
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors"
                                href="#">Fitur</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors" href="#">Solusi
                                Retail</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors" href="#">Solusi
                                F&amp;B</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors"
                                href="#">Enterprise</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-text-main font-bold mb-4">Perusahaan</h4>
                    <ul class="space-y-2">
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors" href="#">Tentang
                                Kami</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors"
                                href="#">Karir</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors"
                                href="#">Blog</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors"
                                href="#">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-text-main font-bold mb-4">Dukungan</h4>
                    <ul class="space-y-2">
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors" href="#">Pusat
                                Bantuan</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors"
                                href="#">Dokumentasi API</a></li>
                        <li><a class="text-text-secondary hover:text-primary text-sm transition-colors" href="#">Status
                                Server</a></li>
                    </ul>
                </div>
            </div>
            <div
                class="border-t border-surface-border pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-text-secondary text-sm">© 2024 Dataprima POS. All rights reserved.</p>
                <div class="flex gap-6">
                    <a class="text-text-secondary hover:text-primary transition-colors" href="#">
                        <span class="sr-only">Facebook</span>
                        <div class="w-5 h-5 bg-current opacity-50 hover:opacity-100 rounded-sm"></div>
                    </a>
                    <a class="text-text-secondary hover:text-primary transition-colors" href="#">
                        <span class="sr-only">Twitter</span>
                        <div class="w-5 h-5 bg-current opacity-50 hover:opacity-100 rounded-sm"></div>
                    </a>
                    <a class="text-text-secondary hover:text-primary transition-colors" href="#">
                        <span class="sr-only">LinkedIn</span>
                        <div class="w-5 h-5 bg-current opacity-50 hover:opacity-100 rounded-sm"></div>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <button id="backToTop" title="Kembali ke Atas">
        <span class="material-symbols-outlined">expand_less</span>
    </button>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            mirror: false,
            disable: 'mobile' ? false : 'mobile', // Aktifkan di mobile
            offset: 100,
        });
    </script>
</body>

</html>