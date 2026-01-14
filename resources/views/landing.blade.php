<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataPrima POS - Solusi Kasir Pintar Bisnis Anda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background-color: #f8fafc;
            color: #334155;
            scroll-behavior: smooth;
        }

        /* 1. CUSTOM NAVBAR */
        .custom-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #2563eb;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        /* 2. HERO SECTION */
        .hero-section {
            padding: 80px 0 80px;
            text-align: center;
            background: radial-gradient(circle at top right, #f0f7ff, #ffffff);
            border-radius: 0 0 60px 60px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .hero-title {
            font-size: 3.8rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        /* Styling Gambar Hero */
        .hero-image-container {
            position: relative;
            padding: 20px;
        }

        .hero-img {
            width: 100%;
            max-width: 600px;
            height: auto;
            border-radius: 20px;
            /* Efek bayangan halus agar gambar terlihat menyatu dengan background */
            filter: drop-shadow(0 20px 50px rgba(37, 99, 235, 0.1));
            animation: floatImage 4s ease-in-out infinite;
        }

        @keyframes floatImage {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Penyesuaian teks hero untuk mode 2 kolom */
        @media (min-width: 992px) {
            .hero-section {
                text-align: left;
                /* Teks jadi rata kiri di desktop */
            }

            .hero-title {
                font-size: 3.5rem;
            }

            .hero-btns {
                justify-content: start !important;
            }
        }

        /* Container untuk Monitor */
        .monitor-wrapper {
            position: relative;
            padding: 15px;
            background: #2d3436;
            /* Warna bingkai monitor */
            border-radius: 15px 15px 5px 5px;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.25), 0 18px 36px -18px rgba(0, 0, 0, 0.3);
        }

        /* Stand/Kaki Monitor */
        .monitor-stand {
            width: 100px;
            height: 40px;
            background: #636e72;
            margin: 0 auto;
            position: relative;
        }

        .monitor-base {
            width: 200px;
            height: 10px;
            background: #2d3436;
            margin: 0 auto;
            border-radius: 5px;
        }

        /* Gambar di dalam layar */
        .screen-content {
            border-radius: 5px;
            overflow: hidden;
            border: 3px solid #1a1a1a;
            background: #fff;
        }

        .hero-img-pos {
            width: 100%;
            height: auto;
            transition: transform 0.5s ease;
        }

        .monitor-wrapper:hover .hero-img-pos {
            transform: scale(1.05);
            /* Efek zoom saat kursor di atas monitor */
        }

        /* Animasi Fade Up Custom */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-cta {
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 10px;
            transition: 0.3s;
        }

        /* 3. PRICING & CARDS */
        .pricing-container {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
            padding: 40px 0;
        }

        .pricing-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 40px 30px;
            flex: 0 1 320px;
            display: flex;
            flex-direction: column;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .pricing-card:hover {
            transform: translateY(-12px);
            border-color: #2563eb;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }

        .btn-pricing {
            padding: 10px 20px !important;
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* FEATURE SECTION STYLE */
        .feature-icon-box {
            width: 60px;
            height: 60px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        /* FAQ STYLE */
        .accordion-item {
            border: 1px solid #e2e8f0;
            border-radius: 15px !important;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .accordion-button:not(.collapsed) {
            background-color: #eff6ff;
            color: #2563eb;
        }

        /* 4. FOOTER */
        .footer-section {
            background-color: #0f172a;
            color: #94a3b8;
            padding: 80px 0 40px;
        }

        .footer-brand {
            color: #3b82f6;
            font-size: 1.8rem;
            font-weight: 800;
            text-decoration: none;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: 0.3s;
        }

        .footer-link:hover {
            color: #3b82f6;
            transform: translateX(5px);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.6rem;
            }
        }

        /* Container Utama */
        .perspective-container {
            perspective: 1200px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Bingkai Monitor Lurus */
        .monitor-wrapper {
            background: #2d3436;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
        }

        .monitor-wrapper:hover {
            transform: rotateY(-5deg) rotateX(0deg);
        }

        .screen-content {
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid #1a1a1a;
            aspect-ratio: 16 / 10;
        }

        .hero-img-pos {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Kaki Monitor */
        .monitor-stand-box {
            margin-top: -5px;
        }

        /* Container Utama (Hapus Perspektif) */
        .monitor-scene {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 500px;
            /* Ukuran sedang yang pas */
        }

        /* Kartu Melayang yang Diperbarui Posisinya */
        .floating-card {
            position: absolute;
            background: white;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 10;
            animation: floatAnim 3s ease-in-out infinite;
            min-width: 160px;
            text-align: left;
        }

        .card-sales {
            top: -20px;
            right: -30px;
            border-left: 4px solid #2563eb;
        }

        .card-order {
            bottom: 40px;
            left: -40px;
            animation-delay: 1.5s;
            border-left: 4px solid #10b981;
        }

        .floating-card h6 {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .floating-card p {
            font-size: 1rem;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
        }

        @keyframes floatAnim {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg custom-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="bi bi-shop me-2"></i>DATAPRIMA <span
                    class="text-dark">POS</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link mx-2 fw-semibold" href="#pricing-section">Harga</a></li>
                    <li class="nav-item"><a class="nav-link mx-2 fw-semibold" href="#feature-section">Fitur Kasir</a>
                    </li>
                    <li class="nav-item"><a class="nav-link mx-2 fw-semibold" href="#faq-section">FAQ</a></li>

                    @guest
                        <li class="nav-item"><a class="nav-link mx-2 fw-semibold" href="{{ route('login') }}">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-cta ms-lg-3"
                                href="{{ route('register') }}">Daftar Sekarang</a></li>
                    @endguest

                    @auth
                        <li class="nav-item">
                            <a class="btn btn-primary btn-cta ms-lg-3" href="{{ url('/home') }}">
                                <i class="bi bi-grid-fill me-2"></i>Dashboard
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    {{-- <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                    <div class="perspective-container">
                        <div class="monitor-wrapper">
                            <div class="screen-content">
                                <img src="{{ asset('assets/img/POS.jpg') }}" alt="DataPrima POS Dashboard"
                                    class="hero-img-pos">
                            </div>
                        </div>
                    </div>

                    <div class="monitor-stand-box text-center">
                        <div style="width: 40px; height: 20px; background: #636e72; margin: 0 auto;"></div>
                        <div
                            style="width: 110px; height: 6px; background: #2d3436; margin: 0 auto; border-radius: 3px;">
                        </div>
                    </div>
                </div> --}}
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-lg-start" data-aos="fade-right">
                    <span class="badge rounded-pill text-primary px-3 py-2 mb-3 fw-bold" style="background: #eff6ff;">
                        #1 POS Terpercaya di Indonesia
                    </span>
                    <h1 class="hero-title">
                        Kelola Toko <br>
                        Lebih <span class="text-primary">Profesional</span>
                    </h1>
                    <p class="text-muted fs-5 mt-4">
                        Pantau stok, penjualan, dan kinerja karyawan langsung dari layar monitor kasir Anda secara
                        real-time.
                    </p>

                    <div class="d-flex gap-3 mt-5 justify-content-center justify-content-lg-start">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary btn-cta shadow-lg px-4">Mulai
                                Sekarang</a>
                        @else
                            <a href="{{ url('/home') }}" class="btn btn-primary btn-cta shadow-lg px-4">Buka Dashboard</a>
                        @endguest
                        <a href="#pricing-section" class="btn btn-outline-primary btn-cta px-4">Lihat Paket</a>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                    <div class="text-center">
                        <div class="monitor-scene">

                            <div class="floating-card card-sales">
                                <h6 class="small text-muted fw-bold mb-1">Total Penjualan</h6>
                                <p class="h6 fw-extrabold text-primary mb-0">Rp 12.450.000</p>
                            </div>

                            <div class="floating-card card-order">
                                <h6 class="small text-muted fw-bold mb-1">Order Terbaru</h6>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <p class="h6 fw-extrabold mb-0">+18 Transaksi</p>
                                </div>
                            </div>

                            <div class="monitor-wrapper">
                                <div class="screen-content">
                                    <img src="{{ asset('assets/img/POS.jpg') }}" alt="Dashboard DataPrima POS"
                                        class="hero-img-pos">
                                </div>
                            </div>

                            <div class="monitor-stand-box">
                                <div style="width: 40px; height: 25px; background: #636e72; margin: 0 auto;"></div>
                                <div
                                    style="width: 120px; height: 8px; background: #2d3436; margin: 0 auto; border-radius: 4px;">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 pt-5" id="feature-section">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold fs-1">Fitur Unggulan DataPrima</h2>
            <p class="text-muted">Segala yang Anda butuhkan untuk operasional toko modern.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 bg-white rounded-4 border h-100">
                    <div class="feature-icon-box"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h5 class="fw-bold">Transaksi Cepat</h5>
                    <p class="text-muted small">Proses checkout dalam hitungan detik. Mendukung barcode scanner dan
                        cetak struk instan.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 bg-white rounded-4 border h-100">
                    <div class="feature-icon-box"><i class="bi bi-box-seam-fill"></i></div>
                    <h5 class="fw-bold">Manajemen Inventori</h5>
                    <p class="text-muted small">Update stok otomatis secara real-time. Notifikasi jika stok menipis
                        agar
                        jualan tetap lancar.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="p-4 bg-white rounded-4 border h-100">
                    <div class="feature-icon-box"><i class="bi bi-graph-up-arrow"></i></div>
                    <h5 class="fw-bold">Laporan Akurat</h5>
                    <p class="text-muted small">Pantau omzet harian, mingguan, hingga laba bersih melalui dashboard
                        yang
                        interaktif.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 pt-5" id="pricing-section">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold fs-1">Paket Harga Berlangganan</h2>
            <p class="text-muted">Pilih paket yang paling pas untuk skala bisnis Anda.</p>
        </div>

        <div class="pricing-container">
            @foreach ($packages as $package)
                <div class="pricing-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="text-center">
                        <h5 class="fw-bold text-uppercase text-primary mb-4" style="letter-spacing: 1px;">
                            {{ $package->name }}</h5>
                        <h3 class="fw-extrabold mb-0" style="font-size: 2.2rem;">Rp
                            {{ number_format($package->price, 0, ',', '.') }}</h3>
                        <p class="text-muted small">per bulan</p>
                    </div>
                    <hr class="my-4">
                    <ul class="list-unstyled text-start mb-5">
                        <li class="mb-3 d-flex align-items-center"><i
                                class="bi bi-check-circle-fill text-success me-3"></i><span>Multi Cabang</span></li>
                        <li class="mb-3 d-flex align-items-center"><i
                                class="bi bi-check-circle-fill text-success me-3"></i><span>Manajemen Stok</span></li>
                        <li class="mb-3 d-flex align-items-center"><i
                                class="bi bi-check-circle-fill text-success me-3"></i><span>Laporan Laba/Rugi</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center"><i
                                class="bi bi-check-circle-fill text-success me-3"></i><span>Multi User</span>
                        </li>
                    </ul>
                    <div class="mt-auto">
                        <button class="btn btn-primary w-100 rounded-pill btn-pricing shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#signupModal" data-codepaket="{{ $package->id }}"
                            data-namapaket="{{ $package->name }} POS" data-harga="{{ $package->price }}">
                            Pilih Paket
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container mt-5 pt-5 mb-5" id="faq-section">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold fs-1">Pertanyaan Umum</h2>
            <p class="text-muted">Segala jawaban yang Anda butuhkan tentang layanan kami.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="accordion" id="accordionFAQ">
                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                                Apakah saya perlu membeli hardware khusus?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Dataprima POS dirancang fleksibel. Anda bisa menggunakan tablet (iPad/Android) atau PC
                                yang sudah Anda miliki. Namun, kami juga menyediakan rekomendasi paket hardware yang
                                teruji untuk performa maksimal.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq2">
                                Apakah data penjualan saya aman?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Sangat aman. Kami menggunakan enkripsi standar bank dan backup server berkala untuk
                                menjamin kerahasiaan dan keamanan data bisnis Anda.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq3">
                                Bagaimana jika saya butuh bantuan teknis?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Tim support kami tersedia 24/7 melalui WhatsApp dan Email. Kami siap memandu Anda mulai
                                dari instalasi hingga penggunaan fitur lanjutan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <a href="#" class="footer-brand mb-3 d-block">DataPrima <span
                            class="text-white">POS</span></a>
                    <p class="pe-lg-5">Membantu ribuan UMKM di Indonesia bertransformasi ke digital. Efisiensi
                        transaksi dan akurasi data adalah prioritas kami.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="text-white fw-bold mb-4">Fitur Utama</h6>
                    <a href="#" class="footer-link">Manajemen Inventori</a>
                    <a href="#" class="footer-link">Laporan Penjualan</a>
                    <a href="#" class="footer-link">Aplikasi Kasir Mobile</a>
                    <a href="#" class="footer-link">Loyalty Program</a>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="text-white fw-bold mb-4">Hubungi Kami</h6>
                    <p class="small mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i> Jakarta, Indonesia</p>
                    <p class="small mb-2"><i class="bi bi-envelope me-2 text-primary"></i> hello@dataprima-pos.com</p>
                    <p class="small"><i class="bi bi-whatsapp me-2 text-primary"></i> +62 812-0000-0000</p>
                </div>
            </div>
            <div class="border-top border-secondary mt-5 pt-4 text-center">
                <p class="small mb-0">&copy; {{ date('Y') }} DataPrima POS. Memajukan Bisnis Lokal.</p>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Konfirmasi Berlangganan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('register') }}" method="GET">
                    <div class="modal-body p-4 text-center">
                        <input type="hidden" name="package_id" id="inputPackageId">
                        <div class="p-3 bg-light rounded-3 mb-3">
                            <h5 id="namaPaketModal" class="fw-bold mb-1"></h5>
                            <h2 class="text-primary fw-bold" id="hargaPaketModal"></h2>
                        </div>
                        <p class="text-muted">Siap untuk mendigitalkan bisnis Anda? Klik lanjut untuk pendaftaran.</p>
                    </div>
                    <div class="modal-footer border-0 pb-4 justify-content-center">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Nanti
                            Dulu</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">Ya,
                            Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out'
        });

        document.addEventListener('DOMContentLoaded', function() {
            const signupModal = document.getElementById('signupModal');
            if (signupModal) {
                signupModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const packageId = button.getAttribute('data-codepaket');
                    const packageName = button.getAttribute('data-namapaket');
                    const packagePrice = button.getAttribute('data-harga');

                    signupModal.querySelector('#namaPaketModal').textContent = packageName;
                    signupModal.querySelector('#hargaPaketModal').textContent = 'Rp ' + new Intl
                        .NumberFormat('id-ID').format(packagePrice);
                    signupModal.querySelector('#inputPackageId').value = packageId;
                });
            }
        });
    </script>
</body>

</html>
