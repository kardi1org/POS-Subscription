<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', 'Nunito', sans-serif;
        }

        /* === Navbar Modern & Responsive === */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            /* height: 70; */
        }

        .navbar .nav-link {
            color: #374151;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .navbar .nav-link:hover {
            color: #2563eb;
        }

        /* Styling khusus untuk menu aktif */
        .nav-link.active-link {
            color: #2563eb !important;
            font-weight: 700 !important;
        }

        /* Perbaikan Menu Mobile */
        @media (max-width: 767.98px) {
            .navbar-collapse {
                padding: 1rem 0;
            }

            .nav-item {
                border-bottom: 1px solid #f1f5f9;
            }

            .nav-item:last-child {
                border-bottom: none;
            }
        }

        /* === Container Layout === */
        main {
            padding-top: 30px;
        }

        /* === Card Pricing Responsive === */
        .pricing-container {
            display: flex;
            /* Memastikan konten ada di tengah secara horizontal */
            justify-content: center;
            /* Memastikan konten ada di tengah secara vertikal jika dibutuhkan */
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
            padding: 20px 15px;
            width: 100%;
        }

        .pricing-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 28px 20px;
            /* Gunakan flex-basis untuk kontrol ukuran di berbagai layar */
            flex: 0 1 320px;
            /* Memberikan margin otomatis kiri-kanan agar tetap center di mobile */
            margin: 0 auto;
            transition: all 0.3s ease;
            list-style: none;
        }

        /* Responsivitas Khusus Mobile */
        @media (max-width: 576px) {
            .pricing-container {
                /* Reset gap agar margin auto bekerja maksimal */
                gap: 20px;
                padding: 10px;
            }

            .pricing-card {
                /* Memastikan kartu mengambil lebar maksimal yang tersedia di HP */
                width: 100%;
                max-width: 340px;
            }
        }

        .pricing-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }

        /* === Table Responsive === */
        .table-container {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            margin-top: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            overflow-x: auto;
            /* Memungkinkan scroll horizontal jika tabel terlalu lebar */
        }

        table {
            width: 100%;
            min-width: 600px;
            /* Mencegah tabel terlalu menciut */
            border-collapse: collapse;
        }

        /* === Form Container === */
        .form-container {
            background: #fff;
            border-radius: 12px;
            padding: 32px 24px;
            max-width: 640px;
            margin: 20px auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        /* Header Card Colors */
        .pricing-card.basic .header {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
        }

        .pricing-card.pro .header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #fff;
        }

        .pricing-card.premium .header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        .pricing-card .header {
            font-size: 1.4rem;
            font-weight: 700;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 18px;
            text-align: center;
        }

        footer {
            color: #374151;
            text-align: center;
            padding: 20px 0;
            font-size: 0.9rem;
            border-top: 1px solid #e5e7eb;
            margin-top: 50px;
        }
    </style>

    <style>
        body {
            background-color: #f8fafc;
            /* Warna background yang sama dengan landing page */
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .login-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 30px 30px 10px;
            text-align: center;
        }

        .login-header h3 {
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-login {
            background-color: #2563eb;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .brand-text {
            color: #2563eb;
            font-weight: 800;
        }
    </style>

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .auth-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 30px 30px 10px;
            text-align: center;
        }

        .auth-header h3 {
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-auth {
            background-color: #2563eb;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .brand-text {
            color: #2563eb;
            font-weight: 800;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    <i class="bi bi-rocket-takeoff-fill me-2"></i>DATAPRIMA
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('home') ? 'active-link' : '' }}"
                                href="{{ url('/home') }}">Home</a>
                        </li>
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/pricing*') ? 'active-link' : '' }}"
                                        href="{{ route('admin.pricing.index') }}">Admin Dashboard</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-semibold" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <footer>
            <div class="container">
                <p class="mb-0">&copy; {{ date('Y') }} Dataprima. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Pastikan dropdown diinisialisasi secara manual jika masih bandel
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 3000
            });
        @endif
    </script>
</body>

</html>
