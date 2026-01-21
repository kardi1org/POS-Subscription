@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="brand-text"><i class="bi bi-envelope-check me-2"></i>DATAPRIMA <span
                            class="text-dark">POS</span></h2>
                    <p class="text-muted">Satu langkah lagi untuk mengaktifkan akun Anda.</p>
                </div>

                <div class="card auth-card">
                    <div class="auth-header">
                        <h3>{{ __('Verifikasi Email') }}</h3>
                    </div>

                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="bi bi-send-check" style="font-size: 3rem; color: #2563eb; opacity: 0.8;"></i>
                        </div>

                        @if (session('resent'))
                            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert"
                                style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                            </div>
                        @endif

                        <p class="text-secondary mb-4">
                            {{ __('Sebelum melanjutkan, silakan periksa email Anda untuk melihat link verifikasi yang kami kirimkan.') }}
                        </p>

                        <p class="small text-muted mb-4">
                            {{ __('Tidak menerima email?') }}
                        </p>

                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-auth w-100 shadow-sm">
                                <i class="bi bi-arrow-repeat me-2"></i>{{ __('Kirim Ulang Email Verifikasi') }}
                            </button>
                        </form>

                        <hr class="my-4" style="opacity: 0.1;">

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="text-decoration-none small text-danger fw-bold">
                            <i class="bi bi-box-arrow-left me-1"></i> Keluar dan Gunakan Akun Lain
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
