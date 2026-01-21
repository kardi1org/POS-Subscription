@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="brand-text"><i class="bi bi-shop me-2"></i>DATAPRIMA <span class="text-dark">POS</span></h2>
                    <p class="text-muted">Mulai kelola bisnis Anda lebih profesional hari ini.</p>
                </div>

                <div class="card auth-card">
                    <div class="auth-header">
                        <h3>{{ __('Daftar Akun') }}</h3>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name"
                                    class="form-label small fw-bold text-muted">{{ __('Nama Lengkap') }}</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email"
                                    class="form-label small fw-bold text-muted">{{ __('Alamat Email') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" placeholder="nama@bisnis.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                    class="form-label small fw-bold text-muted">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="new-password" placeholder="Minimal 8 karakter">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm"
                                    class="form-label small fw-bold text-muted">{{ __('Konfirmasi Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Ulangi password">
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary btn-auth w-100 mb-3">
                                    {{ __('Buat Akun Sekarang') }}
                                </button>

                                <p class="text-center small text-muted">
                                    Sudah punya akun? <a href="{{ route('login') }}"
                                        class="fw-bold text-decoration-none">Masuk di sini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
