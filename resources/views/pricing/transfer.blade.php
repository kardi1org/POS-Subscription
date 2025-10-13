@extends('layouts.app')

@section('content')
    <div class="form-container">
        <h2>Transfer Pembayaran</h2>

        <p>Terima kasih telah mendaftar paket <strong>{{ $pricing->namapaket }}</strong>.</p>
        <p>Silakan lakukan transfer ke rekening berikut:</p>

        <div class="mb-4">
            <strong>Bank:</strong> BCA <br>
            <strong>No Rekening:</strong> 1234567890 <br>
            <strong>Atas Nama:</strong> PT Contoh Perusahaan
        </div>

        <p>Setelah melakukan pembayaran, silakan upload bukti transfer agar pembayaran bisa diverifikasi.</p>

        <div class="d-flex mt-3">
            @if ($pricing->bukti_transfer)
                {{-- Jika sudah upload, tampilkan icon file --}}
                <a href="{{ asset('storage/' . $pricing->bukti_transfer) }}" target="_blank"
                    class="btn btn-outline-primary me-2">
                    <i class="bi bi-file-earmark-text"></i> Lihat Bukti
                </a>
            @else
                {{-- Jika belum upload, tampilkan tombol upload --}}
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-upload"></i> Upload Bukti Transfer
                </button>
            @endif

            <a href="{{ url('home') }}" class="btn btn-success">Kembali ke Beranda</a>
        </div>
    </div>

    <!-- Modal Upload Bukti -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pricings.uploadBukti', $pricing->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Bukti Transfer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bukti" class="form-label">Pilih File (jpg, png, pdf)</label>
                            <input type="file" name="bukti" id="bukti" class="form-control" required>
                        </div>
                        <small class="text-muted">Maksimal ukuran file 2MB.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
