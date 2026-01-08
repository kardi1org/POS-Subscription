@extends('layouts.app')

@section('content')
    <div class="container-fluid px-2">

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
                            <h2 class="fw-bold text-dark mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}</h2>
                            <small class="text-muted">/ 30 days</small>
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
                            @forelse ($pricings as $pricing)
                                {{-- @if ($pricing->status === 'Aktif')
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3" data-bs-toggle="modal"
                                        data-bs-target="#renewModal{{ $pricing->id }}">
                                        <i class="bi bi-arrow-repeat"></i> Renew
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                    @endif --}}
                                <span class="text-muted">-</span>
                            @empty
                                <button class="btn btn-gradient w-100 fw-semibold rounded-pill py-2" data-bs-toggle="modal"
                                    data-bs-target="#signupModal" data-codepaket="{{ $package->id }}"
                                    data-namapaket="{{ $package->name }}" data-harga="{{ $package->price }}">
                                    <i class="bi bi-cart-plus me-1"></i> Get Started
                                </button>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- SUBSCRIPTION STATUS TABLE --}}
        <div class="bg-white rounded-4 shadow-sm p-3 mt-5">
            <h4 class="fw-semibold text-center text-secondary mb-3">Subscription Status</h4>
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
                            <th>Membership</th>
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
                                            class="badge bg-warning-subtle text-warning fw-semibold rounded-pill px-3">Menunggu
                                            Persetujuan Admin</span>
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
                                                                <select class="form-select" name="package_id"
                                                                    id="packageSelect{{ $pricing->id }}" required>
                                                                    @foreach ($packages as $package)
                                                                        <option value="{{ $package->id }}"
                                                                            data-price="{{ $package->price }}"
                                                                            {{ $package->id == $pricing->codepaket ? 'selected' : '' }}>
                                                                            {{ $package->name }} - Rp
                                                                            {{ number_format($package->price, 0, ',', '.') }}
                                                                            / 30
                                                                            days
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
                                                                    <option value="1">30 Hari</option>
                                                                    <option value="3">90 Hari</option>
                                                                    <option value="6">180 Hari</option>
                                                                    <option value="12">360 Hari</option>
                                                                    <option value="24">720 Hari</option>
                                                                    <option value="36">1080 Hari</option>

                                                                </select>
                                                            </div>

                                                            <div id="pricePreview{{ $pricing->id }}"
                                                                class="alert d-none mt-3"></div>

                                                            <div id="downgradeWarning{{ $pricing->id }}"
                                                                class="alert alert-danger mt-3 d-none">
                                                                🚫 Downgrade paket tidak diperbolehkan.
                                                            </div>

                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="submitRenew{{ $pricing->id }}">
                                                                Lanjutkan Pembayaran
                                                            </button>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 🔹 Tombol Riwayat Perpanjangan -->
                                        <button type="button" hidden class="btn btn-info btn-sm ms-2 rounded-pill px-3"
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
                                                                            <td>{{ $renewal->duration }} hari</td>
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
                                <td class="text-center">
                                    @if ($pricing->start_date && $pricing->end_date)
                                        <!-- Tombol Add User -->
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2"
                                            data-bs-toggle="modal" data-bs-target="#addUserModal{{ $pricing->id }}">
                                            <i class="bi bi-person-plus"></i> Add
                                        </button>

                                        <!-- Tombol View User -->
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $pricing->id }}">
                                            <i class="bi bi-people"></i> View
                                        </button>

                                        <!-- Modal Add User -->
                                        <div class="modal fade" id="addUserModal{{ $pricing->id }}" tabindex="-1"
                                            aria-labelledby="addUserModalLabel{{ $pricing->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('membership.store') }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="addUserModalLabel{{ $pricing->id }}">
                                                                Tambah User Membership
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="pricing_id"
                                                                value="{{ $pricing->id }}">
                                                            {{-- <input type="hidden" name="db_database"
                                                            value="{{ $pricing->user->DB_DATABASE }}">
                                                        <input type="hidden" name="db_host"
                                                            value="{{ $pricing->user->DB_HOST }}">
                                                        <input type="hidden" name="db_port"
                                                            value="{{ $pricing->user->DB_PORT }}"> --}}

                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Lengkap</label>
                                                                <input type="text" name="name" class="form-control"
                                                                    required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Password</label>
                                                                <input type="password" name="userpassword"
                                                                    class="form-control" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Level</label>
                                                                <select name="level" class="form-select" required>
                                                                    <option value="">-- Pilih Level --</option>
                                                                    <option value="admin">Admin</option>
                                                                    <option value="kasir">Kasir</option>
                                                                </select>
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

                                        <!-- View User Modal -->
                                        <div class="modal fade" id="viewUserModal{{ $pricing->id }}" tabindex="-1"
                                            aria-labelledby="viewUserModalLabel{{ $pricing->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="viewUserModalLabel{{ $pricing->id }}">
                                                            User Membership - {{ $pricing->namapaket }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        @php
                                                            $users = \App\Models\MembershipUser::where(
                                                                'pricing_id',
                                                                $pricing->id,
                                                            )->get();
                                                        @endphp

                                                        @if ($users->isEmpty())
                                                            <p class="text-muted text-center mb-0">Belum ada user
                                                                membership.
                                                            </p>
                                                        @else
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table table-striped align-middle text-center mb-0">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Nama</th>
                                                                            <th>Email</th>
                                                                            <th>Level</th>
                                                                            <th>Dibuat</th>
                                                                            <th>Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($users as $muser)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $muser->name }}</td>
                                                                                <td>{{ $muser->email }}</td>
                                                                                <td>{{ ucfirst($muser->level) }}</td>
                                                                                <td>{{ $muser->created_at->format('d M Y H:i') }}
                                                                                </td>
                                                                                <td>
                                                                                    <!-- Tombol Edit -->
                                                                                    <button type="button"
                                                                                        class="btn btn-sm btn-outline-primary me-1"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#editMembershipModal{{ $muser->id }}">
                                                                                        <i class="bi bi-pencil-square"></i>
                                                                                    </button>

                                                                                    <!-- Tombol Hapus -->
                                                                                    <form
                                                                                        action="{{ route('membership.destroy', $muser->id) }}"
                                                                                        method="POST" class="d-inline">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-outline-danger"
                                                                                            onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                                                            <i class="bi bi-trash"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Edit User DITARUH DI LUAR modal view user -->
                                        @foreach ($users as $muser)
                                            <div class="modal fade" id="editMembershipModal{{ $muser->id }}"
                                                tabindex="-1"
                                                aria-labelledby="editMembershipModalLabel{{ $muser->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title"
                                                                id="editMembershipModalLabel{{ $muser->id }}">
                                                                Edit User Membership
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form action="{{ route('membership.update', $muser->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama</label>
                                                                    <input type="text" name="name"
                                                                        class="form-control" value="{{ $muser->name }}"
                                                                        required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Level</label>
                                                                    <select name="level" class="form-select" required>
                                                                        <option value="admin"
                                                                            {{ $muser->level == 'admin' ? 'selected' : '' }}>
                                                                            Admin</option>
                                                                        <option value="kasir"
                                                                            {{ $muser->level == 'kasir' ? 'selected' : '' }}>
                                                                            Kasir</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Simpan
                                                                    Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
                                    <option value="1">30 Hari</option>
                                    <option value="3">90 Hari</option>
                                    <option value="6">180 Hari</option>
                                    <option value="12">360 Hari</option>
                                    <option value="24">720 Hari</option>
                                    <option value="36">1080 Hari</option>

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


        <script>
            document.addEventListener('DOMContentLoaded', function() {

                document.querySelectorAll('[id^="renewModal"]').forEach(modal => {
                    modal.addEventListener('shown.bs.modal', function() {

                        const pricingId = modal.id.replace('renewModal', '');
                        const packageSelect = modal.querySelector('[name="package_id"]');
                        const durationInput = modal.querySelector('[name="duration"]');
                        const previewBox = document.getElementById('pricePreview' + pricingId);

                        if (!packageSelect || !durationInput || !previewBox) return;

                        function preview() {
                            if (!packageSelect.value || !durationInput.value) {
                                previewBox.classList.add('d-none');
                                return;
                            }

                            fetch(`/pricing/${pricingId}/preview`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        package_id: packageSelect.value,
                                        duration: durationInput.value
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    previewBox.classList.remove('d-none');

                                    const submitBtn = document.getElementById('submitRenew' +
                                        pricingId);
                                    const downgradeWarning = document.getElementById(
                                        'downgradeWarning' + pricingId);

                                    // Reset
                                    submitBtn.disabled = false;
                                    downgradeWarning.classList.add('d-none');

                                    if (data.status === 'error') {
                                        previewBox.className = 'alert alert-danger mt-3';
                                        previewBox.innerHTML = data.message;
                                        submitBtn.disabled = true;
                                        return;
                                    }

                                    if (data.status === 'downgrade') {
                                        previewBox.className = 'alert alert-warning mt-3';
                                        previewBox.innerHTML = `
                                            <strong>Downgrade Paket</strong><br>
                                            Paket yang dipilih lebih rendah dari paket aktif.
                                        `;

                                        // 🚫 DISABLE SUBMIT
                                        submitBtn.disabled = true;
                                        downgradeWarning.classList.remove('d-none');
                                        return;
                                    }

                                    // ✅ Upgrade / Renewal
                                    const label = data.status === 'upgrade' ?
                                        '<strong>Upgrade Paket</strong>' :
                                        '<strong>Perpanjangan Paket</strong>';

                                    previewBox.className = 'alert alert-info mt-3';
                                    previewBox.innerHTML = `
                                    <strong>${data.status === 'upgrade' ? 'Upgrade Paket' : 'Perpanjangan Paket'}</strong>
                                    <hr class="my-2">

                                    <div class="d-flex justify-content-between">
                                        <span>Harga Paket Baru</span>
                                        <span>Rp ${data.new_price.toLocaleString('id-ID')}</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span>Durasi</span>
                                        <span>${data.duration_days} hari</span>
                                    </div>

                                    ${data.remaining_days > 0 ? `
                                                                        <div class="d-flex justify-content-between text-success">
                                                                            <span>Sisa Hari Lama</span>
                                                                            <span>${data.remaining_days} hari</span>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between text-success">
                                                                            <span>Potongan Pro-rata</span>
                                                                            <span>- Rp ${data.remaining_value.toLocaleString('id-ID')}</span>
                                                                        </div>
                                                                    ` : ''}

                                    <hr class="my-2">

                                    <div class="d-flex justify-content-between fw-bold text-primary fs-5">
                                        <span>Total Bayar</span>
                                        <span>Rp ${data.total.toLocaleString('id-ID')}</span>
                                    </div>

                                    <div class="mt-2 text-muted small">
                                        Masa aktif sampai <strong>${data.newEndDate}</strong>
                                    </div>
                                `;


                                    submitBtn.disabled = false;
                                });

                        }

                        packageSelect.addEventListener('change', preview);
                        durationInput.addEventListener('change', preview);
                    });
                });

            });
        </script>
