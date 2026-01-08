@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Konfirmasi Pembayaran</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <p><strong>Paket:</strong> {{ $renewal->package->name ?? 'Tidak diketahui' }}</p>
                            <p><strong>Durasi:</strong> {{ $renewal->duration * 30 }} hari</p>
                            <p><strong>Total Bayar:</strong>
                                <span class="text-success fw-bold">
                                    Rp {{ number_format($renewal->total_price, 0, ',', '.') }}
                                </span>
                            </p>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h6 class="fw-bold text-secondary">Rekening Tujuan Transfer:</h6>
                            <ul class="list-unstyled">
                                <li><strong>Bank:</strong> BCA</li>
                                <li><strong>Nomor Rekening:</strong> 1234567890</li>
                                <li><strong>Atas Nama:</strong> PT Contoh Digital</li>
                            </ul>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-center gap-2">
                            @if ($renewal->bukti_transfer)
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewProofModal">
                                    <i class="bi bi-eye me-1"></i> Lihat Bukti
                                </button>
                            @else
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="bi bi-upload me-1"></i> Upload Bukti
                                </button>
                            @endif

                            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === Modal Upload Bukti === --}}
    @if (!$renewal->bukti_transfer)
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0 rounded-4">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="uploadModalLabel">
                            <i class="bi bi-cloud-arrow-up me-2"></i>Upload Bukti Pembayaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form action="{{ route('pricing.uploadProof', $renewal->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="bukti" class="form-label">Pilih File Bukti (jpg, jpeg, png, pdf)</label>
                                <input type="file" name="bukti" id="bukti" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle me-1"></i> Kirim Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- === Modal Lihat Bukti === --}}
    @if ($renewal->bukti_transfer)
        <div class="modal fade" id="viewProofModal" tabindex="-1" aria-labelledby="viewProofModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow border-0 rounded-4">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="viewProofModalLabel">
                            <i class="bi bi-eye me-2"></i>Preview Bukti Pembayaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        @php
                            $ext = pathinfo($renewal->bukti_transfer, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset('storage/' . $renewal->bukti_transfer) }}" alt="Bukti Transfer"
                                class="img-fluid rounded shadow-sm">
                        @elseif (strtolower($ext) === 'pdf')
                            <iframe src="{{ asset('storage/' . $renewal->bukti_transfer) }}" width="100%" height="600px"
                                style="border-radius:10px;"></iframe>
                        @else
                            <p class="text-muted">Format file tidak didukung untuk preview.</p>
                            <a href="{{ asset('storage/' . $renewal->bukti_transfer) }}" target="_blank"
                                class="btn btn-primary btn-sm">
                                <i class="bi bi-download me-1"></i> Download Bukti
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
