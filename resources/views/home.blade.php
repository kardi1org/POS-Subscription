@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="text-center mb-2">
            <h1 class="fw-bold display-6 text-dark mb-2">Choose Your Plan</h1>
            <p class="text-muted fs-5">Flexible pricing that scales with your needs.</p>
        </div>

        {{-- PRICING CARDS --}}
        <div class="row justify-content-center g-4">
            @foreach ($packages as $package)
                <div class="col-md-3">
                    <div class="pricing-card border-0 shadow-sm h-100 d-flex flex-column justify-content-between">
                        <div class="p-4 text-center">
                            <h4 class="fw-semibold mb-3 text-primary">{{ $package->name }}</h4>
                            <p class="text-secondary small mb-4">
                                {{ $package->description ?? 'All essential features for getting started.' }}</p>
                            <h2 class="fw-bold text-dark mb-0">${{ $package->price }}</h2>
                            <small class="text-muted">/ month</small>
                            <hr class="my-4">
                            <ul class="list-unstyled text-start small text-secondary mb-4">
                                <li class="mb-2"><i class="bi bi-hdd me-2 text-primary"></i>{{ $package->disk_space }}
                                    Disk Space</li>
                                <li class="mb-2"><i
                                        class="bi bi-cloud-arrow-down me-2 text-primary"></i>{{ $package->bandwidth }}
                                    Bandwidth</li>
                                <li class="mb-2"><i
                                        class="bi bi-envelope me-2 text-primary"></i>{{ $package->email_accounts }} Email
                                    Accounts</li>
                            </ul>
                            <button class="btn btn-gradient w-100 fw-semibold rounded-pill py-2" data-bs-toggle="modal"
                                data-bs-target="#signupModal" data-codepaket="{{ $package->id }}"
                                data-namapaket="{{ $package->name }}" data-harga="{{ $package->price }}">
                                <i class="bi bi-cart-plus me-1"></i> Get Started
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- SUBSCRIPTION STATUS TABLE --}}
        <div class="bg-white rounded-4 shadow-sm p-4 mt-5">
            <h4 class="fw-semibold text-center text-secondary mb-4">Subscription Status</h4>
            <div class="table-responsive">
                <table class="table align-middle table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th>Proof</th>
                            <th>Active Period</th>
                            <th>Renewal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pricings as $pricing)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $pricing->namapaket }}</td>
                                <td>
                                    @if (strtolower($pricing->status) === 'pending' || strtolower($pricing->status) === 'waiting approval')
                                        <span
                                            class="badge bg-warning-subtle text-warning fw-semibold rounded-pill px-3">Waiting
                                            Approval</span>
                                    @else
                                        <span
                                            class="badge bg-success-subtle text-success fw-semibold rounded-pill px-3">{{ ucfirst($pricing->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($pricing->bukti_transfer)
                                        {{-- Tombol Lihat Bukti --}}
                                        <button class="btn btn-light btn-sm border rounded-pill px-3" data-bs-toggle="modal"
                                            data-bs-target="#viewBuktiModal{{ $pricing->id }}">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        <!-- Modal Lihat Bukti -->
                                        <div class="modal fade" id="viewBuktiModal{{ $pricing->id }}" tabindex="-1"
                                            aria-labelledby="viewBuktiModalLabel{{ $pricing->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="viewBuktiModalLabel{{ $pricing->id }}">
                                                            Bukti Transfer - {{ $pricing->namapaket }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        @php
                                                            $ext = pathinfo(
                                                                $pricing->bukti_transfer,
                                                                PATHINFO_EXTENSION,
                                                            );
                                                        @endphp

                                                        {{-- Jika file gambar --}}
                                                        @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                            <img src="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                                alt="Bukti Transfer" class="img-fluid rounded">
                                                            {{-- Jika file pdf --}}
                                                        @elseif (strtolower($ext) === 'pdf')
                                                            <iframe
                                                                src="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                                width="100%" height="500px"></iframe>
                                                        @else
                                                            <p class="text-muted">Format file tidak didukung untuk preview.
                                                            </p>
                                                            <a href="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                                target="_blank" class="btn btn-primary">Download</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($pricing->status === 'Pending' || $pricing->status === 'Waiting Approval')
                                            {{-- Tombol Ganti Bukti --}}
                                            <button type="button" class="btn btn-warning btn-sm ms-2"
                                                data-bs-toggle="modal" data-bs-target="#replaceModal{{ $pricing->id }}">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                            <!-- Modal Ganti Bukti -->
                                            <div class="modal fade" id="replaceModal{{ $pricing->id }}" tabindex="-1"
                                                aria-labelledby="replaceModalLabel{{ $pricing->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('pricings.uploadBukti', $pricing->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="replaceModalLabel{{ $pricing->id }}">
                                                                    Ganti
                                                                    Bukti Transfer</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="bukti{{ $pricing->id }}"
                                                                        class="form-label">Upload
                                                                        Bukti Baru</label>
                                                                    <input type="file" name="bukti"
                                                                        id="bukti{{ $pricing->id }}" class="form-control"
                                                                        required>
                                                                </div>
                                                                <small class="text-muted">Maksimal ukuran file 2MB. File
                                                                    lama
                                                                    akan
                                                                    diganti.</small>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Upload
                                                                    Baru</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        {{-- Jika belum upload --}}
                                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#uploadModal{{ $pricing->id }}">
                                            <i class="bi bi-upload"></i> Upload
                                        </button>
                                        <!-- Modal Upload Bukti -->
                                        <div class="modal fade" id="uploadModal{{ $pricing->id }}" tabindex="-1"
                                            aria-labelledby="uploadModalLabel{{ $pricing->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('pricings.uploadBukti', $pricing->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="uploadModalLabel{{ $pricing->id }}">
                                                                Upload
                                                                Bukti Transfer</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="bukti{{ $pricing->id }}"
                                                                    class="form-label">Pilih
                                                                    File
                                                                    (jpg, png, pdf)
                                                                </label>
                                                                <input type="file" name="bukti"
                                                                    id="bukti{{ $pricing->id }}" class="form-control"
                                                                    required>
                                                            </div>
                                                            <small class="text-muted">Maksimal ukuran file 2MB.</small>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Upload</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                {{-- === Masa Aktif === --}}
                                <td>
                                    @if ($pricing->start_date && $pricing->end_date)
                                        <div>
                                            <small class="text-muted">{{ $pricing->end_date->format('d M Y') }}</small>
                                        </div>

                                        @php
                                            $daysRemaining = now()->diffInDays($pricing->end_date, false);
                                        @endphp

                                        {{-- 🔹 Tampilkan warna berbeda berdasarkan sisa hari --}}
                                        @if ($daysRemaining > 7)
                                            <small class="text-success">
                                                Expiring in {{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }}
                                            </small>
                                        @elseif ($daysRemaining > 0)
                                            <small class="text-warning">
                                                ⚠ Expiring soon ({{ $daysRemaining }}
                                                {{ Str::plural('day', $daysRemaining) }})
                                            </small>
                                        @elseif ($daysRemaining === 0)
                                            <small class="text-danger fw-bold">Expiring today!</small>
                                        @else
                                            <small class="text-danger">Expired {{ abs($daysRemaining) }} days ago</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>



                                {{-- === Perpanjangan === --}}
                                <td>
                                    @if ($pricing->status === 'Aktif')
                                        <!-- Tombol buka modal perpanjangan -->
                                        <button class="btn btn-outline-dark btn-sm rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#renewModal{{ $pricing->id }}">
                                            <i class="bi bi-arrow-repeat"></i> Renew
                                        </button>
                                        <!-- Modal Perpanjangan -->
                                        <div class="modal fade" id="renewModal{{ $pricing->id }}" tabindex="-1"
                                            aria-labelledby="renewModalLabel{{ $pricing->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="renewModalLabel{{ $pricing->id }}">
                                                            Perpanjang / Upgrade Paket - {{ $pricing->namapaket }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <form action="{{ route('pricing.proceedPayment', $pricing->id) }}"
                                                        method="POST" id="renewForm{{ $pricing->id }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <!-- Pilih Paket -->
                                                            <div class="mb-3">
                                                                <label class="form-label">Pilih Paket</label>
                                                                {{-- <select class="form-select" name="package_id"
                                                            id="packageSelect{{ $pricing->id }}" required>
                                                            <option value="">-- Pilih Paket --</option>
                                                            @foreach ($packages as $package)
                                                                <option value="{{ $package->id }}"
                                                                    data-price="{{ $package->price }}"
                                                                    {{ $pricing->package_id == $package->id ? 'selected' : '' }}>
                                                                    {{ $package->name }} - Rp
                                                                    {{ number_format($package->price, 0, ',', '.') }}/bulan
                                                                </option>
                                                            @endforeach
                                                        </select> --}}
                                                                <select class="form-select" name="package_id"
                                                                    id="packageSelect{{ $pricing->id }}" required>
                                                                    @foreach ($packages as $package)
                                                                        <option value="{{ $package->id }}"
                                                                            data-price="{{ $package->price }}"
                                                                            {{ $package->id == $pricing->codepaket ? 'selected' : '' }}>
                                                                            {{ $package->name }} - Rp
                                                                            {{ number_format($package->price, 0, ',', '.') }}/bulan
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>

                                                            <!-- Pilih Durasi -->
                                                            <div class="mb-3">
                                                                <label class="form-label">Pilih Durasi</label>
                                                                <select class="form-select" name="duration"
                                                                    id="durationSelect{{ $pricing->id }}" required>
                                                                    <option value="">-- Pilih Durasi --</option>
                                                                    <option value="1">1 Bulan</option>
                                                                    <option value="3">3 Bulan</option>
                                                                    <option value="6">6 Bulan</option>
                                                                    <option value="12">1 Tahun</option>
                                                                    <option value="24">2 Tahun</option>
                                                                    <option value="36">3 Tahun</option>
                                                                </select>
                                                            </div>

                                                            <!-- Total Harga -->
                                                            <div id="totalContainer{{ $pricing->id }}"
                                                                class="text-center fw-bold fs-5 text-success d-none">
                                                                Total: Rp <span
                                                                    id="totalPrice{{ $pricing->id }}">0</span>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Lanjutkan
                                                                Pembayaran</button>
                                                        </div>
                                                    </form>

                                                    <script>
                                                        document.addEventListener("DOMContentLoaded", function() {
                                                            const formId = "{{ $pricing->id }}";
                                                            const packageSelect = document.getElementById("packageSelect" + formId);
                                                            const durationSelect = document.getElementById("durationSelect" + formId);
                                                            const totalContainer = document.getElementById("totalContainer" + formId);
                                                            const totalPriceEl = document.getElementById("totalPrice" + formId);

                                                            function updateTotal() {
                                                                const selectedPackage = packageSelect.options[packageSelect.selectedIndex];
                                                                const price = parseFloat(selectedPackage.getAttribute("data-price")) || 0;
                                                                const duration = parseInt(durationSelect.value) || 0;

                                                                if (price > 0 && duration > 0) {
                                                                    const total = price * duration;
                                                                    totalPriceEl.textContent = total.toLocaleString("id-ID");
                                                                    totalContainer.classList.remove("d-none");
                                                                } else {
                                                                    totalContainer.classList.add("d-none");
                                                                }
                                                            }

                                                            // Jalankan saat ganti paket atau durasi
                                                            packageSelect.addEventListener("change", updateTotal);
                                                            durationSelect.addEventListener("change", updateTotal);

                                                            // Jalankan sekali saat halaman terbuka (biar langsung muncul jika paket default)
                                                            updateTotal();
                                                        });
                                                    </script>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- 🔹 Tombol Riwayat Perpanjangan -->
                                        <button type="button" hidden class="btn btn-info btn-sm ms-2"
                                            data-bs-toggle="modal" data-bs-target="#historyModal{{ $pricing->id }}">
                                            <i class="bi bi-clock-history"></i> Riwayat
                                        </button>
                                        <!-- Modal Riwayat Perpanjangan -->
                                        <div class="modal fade" id="historyModal{{ $pricing->id }}" tabindex="-1"
                                            aria-labelledby="historyModalLabel{{ $pricing->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title"
                                                            id="historyModalLabel{{ $pricing->id }}">
                                                            Riwayat Perpanjangan - {{ $pricing->namapaket }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $renewals = \App\Models\Renewal::where(
                                                                'pricing_id',
                                                                $pricing->id,
                                                            )
                                                                ->where('status', 'Aktif') // hanya ambil data renewal yang aktif
                                                                ->orderBy('created_at', 'desc')
                                                                ->get();
                                                        @endphp


                                                        @if ($renewals->isEmpty())
                                                            <p class="text-muted text-center mb-0">Belum ada riwayat
                                                                perpanjangan.
                                                            </p>
                                                        @else
                                                            <table class="table table-bordered table-striped align-middle">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Plan</th>
                                                                        <th>Durasi</th>
                                                                        <th>Masa Aktif</th>
                                                                        <th>Tanggal Perpanjangan</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($renewals as $renewal)
                                                                        @php
                                                                            // Ambil data paket berdasarkan new_package
                                                                            $package = \App\Models\Package::find(
                                                                                $renewal->new_package,
                                                                            );
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>{{ $package ? $package->name : '-' }}</td>
                                                                            <td>{{ $renewal->duration }} bulan</td>
                                                                            <td>
                                                                                {{ $renewal->new_end_date ? \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') : '-' }}
                                                                            </td>
                                                                            <td>{{ $renewal->created_at->format('d M Y H:i') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach

                                                                </tbody>
                                                            </table>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted text-center py-3">No subscriptions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('pricing.store') }}" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="signupModalLabel">Sign Up for a Plan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}" />
                            <input type="hidden" id="codepaket" name="codepaket" required readonly>

                            <div class="mb-2">
                                <input type="text" id="namapaket" name="namapaket" class="form-control" readonly
                                    required>
                            </div>

                            <!-- Harga per bulan disembunyikan tapi ikut dikirim -->
                            <input type="hidden" id="hargaPaket" name="harga_paket" readonly>

                            <!-- Pilihan Durasi -->
                            <div class="mb-3">
                                <label for="durasi" class="form-label">Pilih Durasi Langganan</label>
                                <select id="durasi" name="durasi" class="form-select" required>
                                    <option value="">-- Pilih Durasi --</option>
                                    <option value="1">1 Bulan</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">1 Tahun</option>
                                    <option value="24">2 Tahun</option>
                                    <option value="36">3 Tahun</option>
                                </select>
                            </div>

                            <!-- Total Harga -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Total Harga:</label>
                                <div id="totalHarga" class="fs-5 text-primary">Rp 0</div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3"
                                    placeholder="Any comments or requests..."></textarea>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Daftar Langganan
                                </label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        @endsection
        @push('styles')
            <style>
                body {
                    background-color: #f9fafb;
                    font-family: 'Inter', sans-serif;
                    color: #1e293b;
                }

                .pricing-card {
                    border-radius: 16px;
                    background: white;
                    transition: all 0.3s ease;
                }

                .pricing-card:hover {
                    transform: translateY(-6px);
                    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
                }

                .btn-gradient {
                    background: linear-gradient(90deg, #2563eb, #3b82f6);
                    color: #fff;
                    border: none;
                    transition: all 0.25s ease;
                }

                .btn-gradient:hover {
                    background: linear-gradient(90deg, #1d4ed8, #2563eb);
                    transform: scale(1.03);
                }

                .badge {
                    font-size: 0.85rem;
                }

                .bg-warning-subtle {
                    background-color: #fff8e1;
                }

                .bg-success-subtle {
                    background-color: #e6f6ed;
                }

                .bg-danger-subtle {
                    background-color: #fdecea;
                }

                .bg-secondary-subtle {
                    background-color: #f3f4f6;
                }

                .text-warning {
                    color: #ca8a04 !important;
                }

                .text-success {
                    color: #15803d !important;
                }

                .text-danger {
                    color: #dc2626 !important;
                }

                .text-secondary {
                    color: #6b7280 !important;
                }

                table th {
                    font-weight: 600;
                    font-size: 0.9rem;
                }

                table td {
                    font-size: 0.9rem;
                }

                .shadow-sm {
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04) !important;
                }

                @media (max-width: 768px) {
                    .pricing-card {
                        width: 100%;
                    }
                }
            </style>
        @endpush
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const signupModal = document.getElementById('signupModal');

                if (signupModal) {
                    signupModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const codepaket = button.getAttribute('data-codepaket');
                        const namapaket = button.getAttribute('data-namapaket');
                        const harga = parseFloat(button.getAttribute('data-harga')) || 0;

                        document.getElementById('codepaket').value = codepaket;
                        document.getElementById('namapaket').value = namapaket;
                        document.getElementById('hargaPaket').value = harga;

                        signupModal.querySelector('.modal-title').textContent =
                            `Sign Up for Plan: ${namapaket}`;
                        document.getElementById('durasi').value = "";
                        document.getElementById('totalHarga').textContent = "Rp 0";
                    });
                }

                // Hitung total harga saat pilih durasi
                const durasiSelect = document.getElementById('durasi');
                durasiSelect.addEventListener('change', function() {
                    const hargaPaket = parseFloat(document.getElementById('hargaPaket').value) || 0;
                    const durasi = parseInt(this.value) || 0;
                    const total = hargaPaket * durasi;

                    document.getElementById('totalHarga').textContent = formatRupiah(total);
                });

                function formatRupiah(angka) {
                    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            });
        </script>


        <script>
            function goToPayment(pricingId) {
                const packageSelect = document.getElementById(`packageSelect${pricingId}`);
                const durationSelect = document.getElementById(`durationSelect${pricingId}`);

                const packageId = packageSelect.value;
                const duration = durationSelect.value;

                if (!packageId || !duration) {
                    alert('Silakan pilih paket dan durasi terlebih dahulu.');
                    return;
                }

                // Redirect ke halaman pembayaran sambil kirim query string
                window.location.href = `/pricing/${pricingId}/payment?package_id=${packageId}&duration=${duration}`;
            }
        </script>
