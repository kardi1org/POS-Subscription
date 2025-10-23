@extends('layouts.app')

@section('content')
    <div class="row justify-content-center body-container">
        {{-- <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div> --}}
        <h1>Our Pricing Plans</h1>

        <div class="pricing-container">

            <div class="pricing-card basic">
                <div class="header">Basic</div>
                <ul>
                    <li>1 GB Disk Space</li>
                    <li>100 GB Bandwidth</li>
                    <li>5 Email Accounts</li>
                    <li><b>$10 / month</b></li>
                </ul>
                <!-- Tambahkan data-harga -->
                <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#signupModal" data-codepaket="1"
                    data-namapaket="Basic" data-harga="10"> <!-- harga per bulan dalam rupiah -->
                    Sign Up
                </a>
            </div>


            <div class="pricing-card pro">
                <div class="header">Pro</div>
                <ul>
                    <li>5 GB Disk Space</li>
                    <li>500 GB Bandwidth</li>
                    <li>25 Email Accounts</li>
                    <li><b>$25 / month</b></li>
                </ul>
                <!-- Tambahkan data-harga -->
                <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#signupModal" data-codepaket="2"
                    data-namapaket="Pro" data-harga="25"> <!-- harga per bulan dalam rupiah -->
                    Sign Up
                </a>
            </div>

            <div class="pricing-card premium">
                <div class="header">Premium</div>
                <ul>
                    <li>10 GB Disk Space</li>
                    <li>1000 GB Bandwidth</li>
                    <li>50 Email Accounts</li>
                    <li><b>$50 / month</b></li>
                </ul>
                <!-- Tambahkan data-harga -->
                <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#signupModal" data-codepaket="3"
                    data-namapaket="Premium" data-harga="50"> <!-- harga per bulan dalam rupiah -->
                    Sign Up
                </a>
            </div>


        </div>
    </div>
    <!-- TABEL -->
    <div class="table-container">
        <h2>Status Pendaftaran</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Bukti Bayar</th>
                    <th>Masa Aktif</th>
                    <th>Perpanjangan</th> {{-- 🔹 kolom baru --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($pricings as $pricing)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pricing->namapaket }}</td>
                        <td>
                            @if (strtolower($pricing->status) === 'pending')
                                Waiting Approval
                            @else
                                {{ ucfirst($pricing->status) }}
                            @endif
                        </td>
                        <td>
                            @if ($pricing->bukti_transfer)
                                {{-- Tombol Lihat Bukti --}}
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewBuktiModal{{ $pricing->id }}">
                                    <i class="bi bi-file-earmark-text"></i>
                                </button>
                                <!-- Modal Lihat Bukti -->
                                <div class="modal fade" id="viewBuktiModal{{ $pricing->id }}" tabindex="-1"
                                    aria-labelledby="viewBuktiModalLabel{{ $pricing->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewBuktiModalLabel{{ $pricing->id }}">
                                                    Bukti Transfer - {{ $pricing->namapaket }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                @php
                                                    $ext = pathinfo($pricing->bukti_transfer, PATHINFO_EXTENSION);
                                                @endphp

                                                {{-- Jika file gambar --}}
                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ asset('storage/' . $pricing->bukti_transfer) }}"
                                                        alt="Bukti Transfer" class="img-fluid rounded">
                                                    {{-- Jika file pdf --}}
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

                                @if ($pricing->status === 'Pending' || $pricing->status === 'Waiting Approval')
                                    {{-- Tombol Ganti Bukti --}}
                                    <button type="button" class="btn btn-warning btn-sm ms-2" data-bs-toggle="modal"
                                        data-bs-target="#replaceModal{{ $pricing->id }}">
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
                                                        <h5 class="modal-title" id="replaceModalLabel{{ $pricing->id }}">
                                                            Ganti
                                                            Bukti Transfer</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="bukti{{ $pricing->id }}" class="form-label">Upload
                                                                Bukti Baru</label>
                                                            <input type="file" name="bukti"
                                                                id="bukti{{ $pricing->id }}" class="form-control"
                                                                required>
                                                        </div>
                                                        <small class="text-muted">Maksimal ukuran file 2MB. File lama akan
                                                            diganti.</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Upload Baru</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                {{-- Jika belum upload --}}
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#uploadModal{{ $pricing->id }}">
                                    <i class="bi bi-upload"></i>
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
                                                    <h5 class="modal-title" id="uploadModalLabel{{ $pricing->id }}">
                                                        Upload
                                                        Bukti Transfer</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="bukti{{ $pricing->id }}" class="form-label">Pilih
                                                            File
                                                            (jpg, png, pdf)
                                                        </label>
                                                        <input type="file" name="bukti"
                                                            id="bukti{{ $pricing->id }}" class="form-control" required>
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
                                    <small>
                                        {{ $pricing->end_date->format('d M Y') }}
                                    </small>
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
                                        ⚠ Expiring soon ({{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }})
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
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#renewModal{{ $pricing->id }}">
                                    <i class="bi bi-arrow-clockwise"></i> Perpanjang
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
                                                        Total: Rp <span id="totalPrice{{ $pricing->id }}">0</span>
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
                                <button type="button" hidden class="btn btn-info btn-sm ms-2" data-bs-toggle="modal"
                                    data-bs-target="#historyModal{{ $pricing->id }}">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </button>
                                <!-- Modal Riwayat Perpanjangan -->
                                <div class="modal fade" id="historyModal{{ $pricing->id }}" tabindex="-1"
                                    aria-labelledby="historyModalLabel{{ $pricing->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="historyModalLabel{{ $pricing->id }}">
                                                    Riwayat Perpanjangan - {{ $pricing->namapaket }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                @php
                                                    $renewals = \App\Models\Renewal::where('pricing_id', $pricing->id)
                                                        ->where('status', 'Aktif') // hanya ambil data renewal yang aktif
                                                        ->orderBy('created_at', 'desc')
                                                        ->get();
                                                @endphp


                                                @if ($renewals->isEmpty())
                                                    <p class="text-muted text-center mb-0">Belum ada riwayat perpanjangan.
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
                @endforeach

                @forelse($pricings as $pricing)
                @empty
                    <tr>
                        <td colspan="7">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('pricing.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Sign Up for a Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}" />
                    <input type="hidden" id="codepaket" name="codepaket" required readonly>

                    <div class="mb-2">
                        <input type="text" id="namapaket" name="namapaket" class="form-control" readonly required>
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
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                            checked>
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
