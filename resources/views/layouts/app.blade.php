<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Tambahkan di bagian <head> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">





    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
        }

        .body-container {
            font-family: Arial, sans-serif;
            /* background: #f4f4f4; */
            margin: 0;
            padding: 10px;
            text-align: center;
        }

        h1 {
            margin-bottom: 40px;
        }

        .pricing-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .pricing-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
            flex: 1 1 280px;
            /* Responsive width */
        }

        .pricing-card:hover {
            transform: translateY(-5px);
        }

        .pricing-card .header {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            background: #4CAF50;
            padding: 15px;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px -20px;
        }

        .pricing-card ul {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .pricing-card ul li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .pricing-card ul li:last-child {
            border-bottom: none;
        }

        .pricing-card b {
            font-size: 20px;
            color: #333;
        }

        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #45a049;
        }

        /* Optional: Color variants for each plan */
        .pricing-card.basic .header {
            background: #4CAF50;
        }

        /* Green */
        .pricing-card.pro .header {
            background: #2196F3;
        }

        /* Blue */
        .pricing-card.premium .header {
            background: #FF9800;
        }

        /* Orange */

        /* Responsive: Stack cards on small screens */
        @media (max-width: 768px) {
            .pricing-container {
                flex-direction: column;
                align-items: center;
            }
        }

        /* Table Styling */
        .table-container {
            margin-top: 10px;
            /* background: #fff; */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #4CAF50;
            color: white;
        }

        table th,
        table td {
            padding: 12px 16px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-container {
            margin-top: 20px;
            max-width: 600px;
            background: #fff;
            padding: 30px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: auto;
            margin-right: auto;
            text-align: left;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-container .button {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }
    </style>
    <style>
        .pagination {
            margin-top: 10px;
            font-size: 0.875rem;
            /* lebih kecil */
        }
    </style>

    <style>
        body {
            background: #f9fafb;
            font-family: 'Segoe UI', sans-serif;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #374151;
        }

        /* Pricing Cards */
        .pricing-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .pricing-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            width: 280px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }

        .pricing-card .header {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2563eb;
        }

        .pricing-card ul {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
            color: #4b5563;
        }

        .pricing-card li {
            margin: 8px 0;
        }

        .pricing-card .button {
            display: inline-block;
            background: #60a5fa;
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .pricing-card .button:hover {
            background: #3b82f6;
        }

        /* Table Style */
        .table-container {
            margin-top: 50px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f3f4f6;
        }

        thead th {
            padding: 12px;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        tbody td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #4b5563;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        /* Button Soft Colors */
        .btn-success {
            background-color: #86efac !important;
            border: none;
            color: #166534;
        }

        .btn-warning {
            background-color: #fde68a !important;
            border: none;
            color: #92400e;
        }

        .btn-primary {
            background-color: #93c5fd !important;
            border: none;
            color: #1e3a8a;
        }

        .btn-secondary {
            background-color: #e5e7eb !important;
            border: none;
            color: #374151;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>


</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        {{-- Menu Home --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'fw-bold text-primary' : '' }}"
                                href="{{ url('/') }}">
                                Home
                            </a>
                        </li>

                        {{-- Menu Admin Dashboard hanya untuk admin --}}
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/pricing*') ? 'fw-bold text-primary' : '' }}"
                                        href="{{ route('admin.pricing.index') }}">
                                        Admin Dashboard
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>



                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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
            {{-- Flash Message --}}
            {{-- @if (session('success'))
                <div class="alert alert-success text-center mx-auto" style="max-width: 600px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center mx-auto" style="max-width: 600px;">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mx-auto" style="max-width: 600px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}

            @yield('content')
        </main>

    </div>
    <!-- SweetAlert CDN -->
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
