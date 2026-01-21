@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="brand-text"><i class="bi bi-shield-lock me-2"></i>DATAPRIMA <span class="text-dark">POS</span>
                    </h2>
                    <p class="text-muted">Amankan kembali akun Anda dengan password baru.</p>
                </div>

                <div class="card auth-card">
                    <div class="auth-header">
                        <h3>{{ __('Reset Password') }}</h3>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email"
                                    class="form-label small fw-bold text-muted">{{ __('Alamat Email') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                    placeholder="nama@email.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                    class="form-label small fw-bold text-muted">{{ __('Password Baru') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="new-password" placeholder="Masukkan password baru">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm"
                                    class="form-label small fw-bold text-muted">{{ __('Konfirmasi Password Baru') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Ulangi password baru">
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary btn-auth w-100">
                                    {{ __('Perbarui Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
