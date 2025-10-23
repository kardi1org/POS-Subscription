<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Bootstrap -->
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

        /* === Navbar Modern === */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .navbar .nav-link {
            color: #374151;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .navbar .nav-link:hover {
            color: #2563eb;
        }

        .navbar .fw-bold.text-primary {
            color: #2563eb !important;
        }

        /* === Container Layout === */
        main {
            padding-top: 30px;
        }

        /* === Card Pricing Modern === */
        .pricing-container {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .pricing-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 28px 20px;
            width: 300px;
            transition: all 0.3s ease;
        }

        .pricing-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }

        .pricing-card .header {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 18px;
        }

        .pricing-card.basic .header {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .pricing-card.pro .header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .pricing-card.premium .header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .pricing-card ul {
            list-style: none;
            padding: 0;
            color: #4b5563;
        }

        .pricing-card li {
            margin: 10px 0;
            border-bottom: 1px dashed #e5e7eb;
            padding-bottom: 6px;
        }

        .pricing-card .button {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }

        .pricing-card .button:hover {
            background: #1d4ed8;
        }

        /* === Table Modern === */
        .table-container {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            margin-top: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f3f4f6;
        }

        thead th {
            color: #374151;
            font-weight: 600;
            padding: 12px;
            border-bottom: 2px solid #e5e7eb;
            text-align: center;
        }

        tbody td {
            padding: 12px;
            color: #4b5563;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        /* === Form Container === */
        .form-container {
            background: #fff;
            border-radius: 12px;
            padding: 32px 24px;
            max-width: 640px;
            margin: 40px auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #1e293b;
            font-weight: 600;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
            padding: 10px 12px;
        }

        .form-container .button {
            width: 100%;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .form-container .button:hover {
            background: #1d4ed8;
        }

        /* === Buttons Variants === */
        .btn-primary {
            background-color: #2563eb !important;
            color: #fff !important;
            border: none;
        }

        .btn-success {
            background-color: #22c55e !important;
            color: #fff !important;
            border: none;
        }

        .btn-warning {
            background-color: #f59e0b !important;
            color: #fff !important;
            border: none;
        }

        .btn-secondary {
            background-color: #6b7280 !important;
            color: #fff !important;
            border: none;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .pagination {
            margin-top: 10px;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-md sticky-top">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'fw-bold text-primary' : '' }}"
                                href="{{ url('/') }}">Home</a>
                        </li>
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/pricing*') ? 'fw-bold text-primary' : '' }}"
                                        href="{{ route('admin.pricing.index') }}">Admin Dashboard</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right -->
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
                                    role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- MAIN -->
        <main class="py-2">
            @yield('content')
        </main>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
                timer: 3000,
                showConfirmButton: true
            });
        @endif
    </script>
</body>

</html>
