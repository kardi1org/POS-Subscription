@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="brand-text"><i class="bi bi-shop me-2"></i>DATAPRIMA <span class="text-dark">POS</span></h2>
                    <p class="text-muted">Selamat datang kembali, silakan masuk ke akun Anda.</p>
                </div>

                <div class="card login-card">
                    <div class="login-header">
                        <h3>{{ __('Login') }}</h3>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email"
                                    class="form-label small fw-bold text-muted">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="nama@email.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                    class="form-label small fw-bold text-muted">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" placeholder="••••••••">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted" for="remember">
                                        {{ __('Ingat Saya') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small fw-bold" href="{{ route('password.request') }}">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                    {{ __('Masuk ke Dashboard') }}
                                </button>

                                <p class="text-center small text-muted">
                                    Belum punya akun? <a href="{{ route('register') }}"
                                        class="fw-bold text-decoration-none">Daftar Sekarang</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
