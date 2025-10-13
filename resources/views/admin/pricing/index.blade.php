@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Pendaftaran Paket</h1>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif --}}

        {{-- Form Pencarian --}}
        <form method="GET" action="{{ route('admin.pricing.index') }}" class="mb-3 d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                placeholder="Cari paket, email, status...">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        {{-- Tabel --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paket</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Bukti Bayar</th>
                    <th>Masa Aktif</th>
                    <th>Aksi Status</th> {{-- Kolom baru --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($pricings as $pricing)
                    <tr>
                        <td>{{ $loop->iteration + ($pricings->currentPage() - 1) * $pricings->perPage() }}</td>
                        <td>{{ $pricing->namapaket }}</td>
                        <td>{{ $pricing->email }}</td>
                        <td>
                            @php
                                $statusText = match ($pricing->status) {
                                    'Aktif' => 'Active',
                                    'Pending' => 'Waiting Approval',
                                    'Nonaktif' => 'Inactive',
                                    default => ucfirst($pricing->status),
                                };

                                $badgeColor = match ($pricing->status) {
                                    'Aktif' => 'bg-success',
                                    'Pending' => 'bg-warning text-dark',
                                    'Waiting Approval' => 'bg-warning text-dark',
                                    'Nonaktif' => 'bg-secondary',
                                    default => 'bg-light text-dark',
                                };
                            @endphp

                            <span class="badge {{ $badgeColor }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        <td>
                            @if ($pricing->bukti_transfer)
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#buktiModal{{ $pricing->id }}">
                                    <i class="bi bi-file-earmark-text"></i> Lihat Bukti
                                </button>

                                <!-- Modal Bukti -->
                                <div class="modal fade" id="buktiModal{{ $pricing->id }}" tabindex="-1"
                                    aria-labelledby="buktiModalLabel{{ $pricing->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="buktiModalLabel{{ $pricing->id }}">
                                                    Bukti Transfer - {{ $pricing->namapaket }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                @php
                                                    $ext = pathinfo($pricing->bukti_transfer, PATHINFO_EXTENSION);
                                                @endphp

                                                {{-- Jika gambar --}}
                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                        alt="Bukti Transfer" class="img-fluid rounded">
                                                    {{-- Jika PDF --}}
                                                @elseif (strtolower($ext) === 'pdf')
                                                    <iframe src="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                        width="100%" height="500px"></iframe>
                                                @else
                                                    <p class="text-muted">Format file tidak didukung untuk preview.</p>
                                                    <a href="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                        target="_blank" class="btn btn-primary">Download</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">Belum upload</span>
                            @endif
                        </td>
                        <td>
                            @if ($pricing->status === 'Aktif' && $pricing->start_date && $pricing->end_date)
                                <small>{{ $pricing->start_date->format('d M Y') }} -
                                    {{ $pricing->end_date->format('d M Y') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>


                        {{-- Kolom Aksi Status --}}
                        <td>
                            @if ($pricing->bukti_transfer)
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#statusModal{{ $pricing->id }}">
                                    <i class="bi bi-toggle2-on"></i> Ubah Status
                                </button>

                                <!-- Modal Status -->
                                <div class="modal fade" id="statusModal{{ $pricing->id }}" tabindex="-1"
                                    aria-labelledby="statusModalLabel{{ $pricing->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.pricing.updateStatus', $pricing->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{ $pricing->id }}">
                                                        Ubah Status - {{ $pricing->namapaket }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Pilih status baru untuk <strong>{{ $pricing->email }}</strong>:</p>
                                                    <select name="status" class="form-select mb-3"
                                                        id="statusSelect{{ $pricing->id }}" required>
                                                        <option value="waiting approval"
                                                            {{ $pricing->status === 'waiting approval' ? 'selected' : '' }}>
                                                            Waiting Approval
                                                        </option>
                                                        <option value="aktif"
                                                            {{ $pricing->status === 'aktif' ? 'selected' : '' }}>Aktif
                                                        </option>
                                                        <option value="nonaktif"
                                                            {{ $pricing->status === 'nonaktif' ? 'selected' : '' }}>
                                                            Nonaktif
                                                        </option>
                                                    </select>

                                                    {{-- Input masa aktif, muncul hanya jika status = aktif --}}
                                                    <div id="masaAktifContainer{{ $pricing->id }}" style="display: none;">
                                                        <label for="start_date{{ $pricing->id }}"
                                                            class="form-label">Tanggal Mulai</label>
                                                        <input type="date" name="start_date"
                                                            id="start_date{{ $pricing->id }}" class="form-control mb-3"
                                                            value="{{ $pricing->start_date ? $pricing->start_date->format('Y-m-d') : '' }}">

                                                        <label for="end_date{{ $pricing->id }}" class="form-label">Tanggal
                                                            Berakhir</label>
                                                        <input type="date" name="end_date"
                                                            id="end_date{{ $pricing->id }}" class="form-control"
                                                            value="{{ $pricing->end_date ? $pricing->end_date->format('Y-m-d') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Script untuk tampilkan input masa aktif hanya jika status aktif --}}
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const select{{ $pricing->id }} = document.getElementById('statusSelect{{ $pricing->id }}');
                                        const masaAktifContainer{{ $pricing->id }} = document.getElementById(
                                            'masaAktifContainer{{ $pricing->id }}');

                                        function toggleMasaAktif() {
                                            masaAktifContainer{{ $pricing->id }}.style.display = select{{ $pricing->id }}.value ===
                                                'aktif' ? 'block' : 'none';
                                        }

                                        select{{ $pricing->id }}.addEventListener('change', toggleMasaAktif);
                                        toggleMasaAktif(); // Jalankan saat modal dibuka pertama kali
                                    });
                                </script>
                            @else
                                <span class="text-muted">Belum bisa ubah status</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="d-lg-block justify-content-center mt-2">
            {{ $pricings->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection
