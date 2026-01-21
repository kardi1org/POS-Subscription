@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="brand-text"><i class="bi bi-question-circle me-2"></i>DATAPRIMA <span
                            class="text-dark">POS</span></h2>
                    <p class="text-muted">Jangan khawatir, kami akan membantu Anda memulihkan akses akun Anda.</p>
                </div>

                <div class="card auth-card">
                    <div class="auth-header">
                        <h3>{{ __('Lupa Password?') }}</h3>
                    </div>

                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert"
                                style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                            </div>
                        @endif

                        <p class="small text-muted mb-4 text-center">
                            Masukkan alamat email yang terdaftar. Kami akan mengirimkan instruksi untuk membuat password
                            baru ke email Anda.
                        </p>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email"
                                    class="form-label small fw-bold text-muted">{{ __('Alamat Email') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="nama@email.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary btn-auth w-100 mb-3">
                                    {{ __('Kirim Link Pemulihan') }}
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
