@extends('layouts.app')

@section('content')
    <div class="container py-2">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex justify-content-end">
                <form method="GET" action="{{ route('admin.pricing.index') }}" class="d-flex align-items-center"
                    style="gap: 6px;">
                    <div class="input-group input-group-sm" style="max-width: 230px;">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control border-start-0 shadow-none" placeholder="Cari...">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm">
                        Cari
                    </button>
                </form>
            </div>
        </div>


        {{-- Tabel Data --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-gradient text-gray-50"
                style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Pendaftaran & Perpanjangan Paket</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Paket</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Durasi</th>
                                <th>Total Bayar</th>
                                <th>Bukti Bayar</th>
                                <th>Masa Aktif</th>
                                <th>Aksi Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pricings as $pricing)
                                @php
                                    $waitingRenewals = \App\Models\Renewal::where('pricing_id', $pricing->id)
                                        ->where('status', 'waiting approval')
                                        ->get();
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $loop->iteration + ($pricings->currentPage() - 1) * $pricings->perPage() }}</td>
                                    <td>
                                        @if ($waitingRenewals->isNotEmpty())
                                            @foreach ($waitingRenewals as $renewal)
                                                {{ \App\Models\Package::find($renewal->new_package)->name ?? '-' }}
                                            @endforeach
                                        @else
                                            {{ $pricing->namapaket }}
                                        @endif
                                    </td>
                                    <td>{{ $pricing->email }}</td>
                                    <td>
                                        @php
                                            $badgeColor = match ($pricing->status) {
                                                'Aktif' => 'bg-success-subtle text-success',
                                                'Pending', 'Waiting Approval' => 'bg-warning-subtle text-warning',
                                                'Nonaktif' => 'bg-secondary',
                                                default => 'bg-light text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }} fw-semibold rounded-pill px-3">
                                            {{ $pricing->status === 'Pending' || $pricing->status === 'Waiting Approval' ? 'Menunggu Persetujuan' : ucfirst($pricing->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $waitingRenewals->isNotEmpty() ? $waitingRenewals->last()->duration : $pricing->durasi }}
                                        bulan
                                    </td>
                                    <td>Rp
                                        {{ number_format(
                                            $waitingRenewals->isNotEmpty()
                                                ? $waitingRenewals->last()->total_price
                                                : ($pricing->durasi ?? 0) * ($pricing->harga_paket ?? 0),
                                            0,
                                            ',',
                                            '.',
                                        ) }}
                                    </td>
                                    <td>
                                        @if ($pricing->bukti_transfer)
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#buktiModal{{ $pricing->id }}">
                                                <i class="bi bi-eye"></i> Lihat
                                            </button>

                                            {{-- Modal Bukti --}}
                                            <div class="modal fade" id="buktiModal{{ $pricing->id }}" tabindex="-1"
                                                aria-labelledby="buktiModalLabel{{ $pricing->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">
                                                                Bukti Transfer - {{ $pricing->namapaket }}
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center p-4">
                                                            @php
                                                                $ext = pathinfo(
                                                                    $pricing->bukti_transfer,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                            @endphp
                                                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                                <img src="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                                    class="img-fluid rounded shadow-sm">
                                                            @elseif (strtolower($ext) === 'pdf')
                                                                <iframe
                                                                    src="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                                    width="100%" height="500px"
                                                                    class="border rounded"></iframe>
                                                            @else
                                                                <p class="text-muted">Format file tidak didukung.</p>
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
                                        @if ($pricing->start_date && $pricing->end_date)
                                            <small>{{ $pricing->start_date->format('d M Y') }} -
                                                {{ $pricing->end_date->format('d M Y') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Tombol Status --}}
                                    <td>
                                        @if ($pricing->bukti_transfer)
                                            @if ($waitingRenewals->isNotEmpty())
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#waitingRenewalModal{{ $pricing->id }}">
                                                    <i class="bi bi-hourglass-split"></i> Aktifkan Perpanjangan
                                                </button>
                                                <!-- Modal Daftar Perpanjangan Waiting Approval -->
                                                <div class="modal fade" id="waitingRenewalModal{{ $pricing->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="waitingRenewalModalLabel{{ $pricing->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning text-dark">
                                                                <h5 class="modal-title"
                                                                    id="waitingRenewalModalLabel{{ $pricing->id }}">
                                                                    Daftar Perpanjangan Menunggu Persetujuan
                                                                    ({{ $pricing->namapaket }})
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-bordered table-sm align-middle">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Plan</th>
                                                                            <th>Durasi</th>
                                                                            <th>Masa Aktif</th>
                                                                            <th>Total Bayar</th>
                                                                            <th>Bukti Transfer</th>
                                                                            <th>Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($waitingRenewals as $index => $renewal)
                                                                            @php
                                                                                // Ambil data paket berdasarkan new_package
                                                                                $package = \App\Models\Package::find(
                                                                                    $renewal->new_package,
                                                                                );
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{{ $index + 1 }}</td>

                                                                                <td>{{ $package ? $package->name : '-' }}
                                                                                </td>
                                                                                <td>{{ $renewal->duration }} bulan</td>
                                                                                <td>{{ $renewal->new_end_date ? \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') : '-' }}
                                                                                </td>
                                                                                <td>Rp
                                                                                    {{ number_format($renewal->total_price, 0, ',', '.') }}
                                                                                </td>
                                                                                <td>
                                                                                    @if ($renewal->bukti_transfer)
                                                                                        <a href="{{ asset('storage/' . $renewal->bukti_transfer) }}"
                                                                                            target="_blank"
                                                                                            class="btn btn-sm btn-outline-primary">
                                                                                            Lihat Bukti
                                                                                        </a>
                                                                                    @else
                                                                                        <span class="text-muted">Belum
                                                                                            ada</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <form
                                                                                        action="{{ route('pricing.activateRenewal', $renewal->id) }}"
                                                                                        method="POST">
                                                                                        @csrf
                                                                                        <button type="submit"
                                                                                            class="btn btn-success btn-sm">
                                                                                            <i
                                                                                                class="bi bi-check2-circle"></i>
                                                                                            Aktifkan
                                                                                        </button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif (!$pricing->start_date)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#statusModal{{ $pricing->id }}">
                                                    <i class="bi bi-toggle2-on"></i> Ubah Status
                                                </button>

                                                <!-- Modal Status -->
                                                <div class="modal fade" id="statusModal{{ $pricing->id }}"
                                                    tabindex="-1" aria-labelledby="statusModalLabel{{ $pricing->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ route('admin.pricing.updateStatus', $pricing->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="statusModalLabel{{ $pricing->id }}">
                                                                        Ubah Status - {{ $pricing->namapaket }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Tutup"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Pilih status baru untuk
                                                                        <strong>{{ $pricing->email }}</strong>:
                                                                    </p>
                                                                    <select name="status" class="form-select mb-3"
                                                                        id="statusSelect{{ $pricing->id }}" required>
                                                                        <option value="waiting approval"
                                                                            {{ $pricing->status === 'waiting approval' ? 'selected' : '' }}>
                                                                            Menunggu Persetujuan
                                                                        </option>
                                                                        <option value="aktif"
                                                                            {{ $pricing->status === 'aktif' ? 'selected' : '' }}>
                                                                            Aktif
                                                                        </option>
                                                                        <option value="nonaktif"
                                                                            {{ $pricing->status === 'nonaktif' ? 'selected' : '' }}>
                                                                            Nonaktif
                                                                        </option>
                                                                    </select>

                                                                    {{-- Input masa aktif, muncul hanya jika status = aktif --}}
                                                                    <div id="masaAktifContainer{{ $pricing->id }}"
                                                                        style="display: none;">
                                                                        <label for="start_date{{ $pricing->id }}"
                                                                            class="form-label">Tanggal Mulai</label>
                                                                        <input type="date" name="start_date"
                                                                            id="start_date{{ $pricing->id }}"
                                                                            class="form-control mb-3"
                                                                            value="{{ now()->format('Y-m-d') }}">

                                                                        <label for="end_date{{ $pricing->id }}"
                                                                            class="form-label">Tanggal Berakhir</label>
                                                                        <input type="date" name="end_date"
                                                                            id="end_date{{ $pricing->id }}"
                                                                            class="form-control" readonly>
                                                                    </div>

                                                                    <script>
                                                                        document.addEventListener('DOMContentLoaded', function() {
                                                                            const startInput = document.getElementById('start_date{{ $pricing->id }}');
                                                                            const endInput = document.getElementById('end_date{{ $pricing->id }}');
                                                                            const durationMonths = {{ $pricing->durasi ?? 1 }}; // durasi dari tabel pricing (bulan)

                                                                            function updateEndDate() {
                                                                                const startDate = new Date(startInput.value);
                                                                                if (isNaN(startDate)) return; // jika tanggal belum valid

                                                                                const endDate = new Date(startDate);
                                                                                endDate.setMonth(endDate.getMonth() + durationMonths);

                                                                                // Format YYYY-MM-DD
                                                                                endInput.value = endDate.toISOString().split('T')[0];
                                                                            }

                                                                            // Set default saat pertama kali
                                                                            updateEndDate();

                                                                            // Update otomatis ketika start date berubah
                                                                            startInput.addEventListener('change', updateEndDate);
                                                                        });
                                                                    </script>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
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
                                                <span class="text-muted">-</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Belum bisa ubah</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Tidak ada data ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-lg-block justify-content-center mt-2">
            {{ $pricings->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .modal-content {
            border-radius: 16px;
            overflow: hidden;
        }

        .table> :not(caption)>*>* {
            vertical-align: middle;
        }
    </style>
@endsection
