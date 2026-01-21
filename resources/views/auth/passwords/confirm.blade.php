@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="brand-text"><i class="bi bi-shield-lock-fill me-2"></i>DATAPRIMA <span
                            class="text-dark">POS</span></h2>
                    <p class="text-muted">Demi keamanan, silakan konfirmasi password Anda.</p>
                </div>

                <div class="card auth-card">
                    <div class="auth-header">
                        <h3>{{ __('Konfirmasi Password') }}</h3>
                    </div>

                    <div class="card-body p-4">
                        <p class="small text-center text-secondary mb-4">
                            {{ __('Ini adalah area yang aman. Silakan masukkan password Anda sebelum melanjutkan ke tahap berikutnya.') }}
                        </p>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="password"
                                    class="form-label small fw-bold text-muted">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" autofocus placeholder="Masukkan password Anda">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary btn-auth w-100 mb-3">
                                    {{ __('Konfirmasi Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="btn btn-link text-decoration-none small"
                                            href="{{ route('password.request') }}">
                                            {{ __('Lupa Password Anda?') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
