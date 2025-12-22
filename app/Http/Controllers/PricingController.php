<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use App\Models\Package;
use App\Models\Pricing;
use App\Models\Renewal;
use Illuminate\Http\Request;
use App\Mail\StatusAktifMail;
use App\Mail\InvoicePaymentMail;
use App\Mail\RenewalNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\BuktiTransferUploadedMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class PricingController extends Controller
{

    public function transfer($id)
    {
        $pricing = Pricing::findOrFail($id); // Ambil data pendaftaran berdasarkan ID

        return view('pricing.transfer', compact('pricing'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Ambil data pricing
        $pricing = Pricing::findOrFail($id);

        if ($request->hasFile('bukti')) {
            // Simpan file ke folder storage/app/public/bukti-transfer
            $path = $request->file('bukti')->store('bukti-transfer', 'public');

            // Update bukti_transfer di tabel pricing
            $pricing->bukti_transfer = $path;
            $pricing->status = 'Waiting Approval'; // otomatis ubah status
            $pricing->save();

            // Update juga di tabel renewals berdasarkan pricing_id
            $renewal = \App\Models\Renewal::where('pricing_id', $pricing->id)
                ->latest() // ambil renewal terakhir
                ->first();

            if ($renewal) {
                $renewal->bukti_transfer = $path;
                $renewal->status = 'Waiting Approval';
                $renewal->save();
            }
            // Ambil semua email admin
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();

            if (!empty($adminEmails)) {
                // Kirim email sekaligus ke semua admin
                Mail::to($adminEmails)
                    ->send(new BuktiTransferUploadedMail($pricing, 'pricing'));
            }
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }


    public function index(Request $request)
    {
        $search = $request->input('search');

        $pricings = \App\Models\Pricing::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('namapaket', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // ===============================
        // 1️⃣ Ambil database yang SUDAH dipakai
        // ===============================
        $usedDatabases = DB::table('users')
            ->whereNotNull('db_database')
            ->pluck('db_database')
            ->map(fn ($db) => strtolower($db))
            ->toArray();

        // ===============================
        // 2️⃣ Ambil semua database POS
        // ===============================
        $databases = collect(DB::select("SHOW DATABASES"))
            ->pluck('Database')
            ->filter(function ($db) use ($usedDatabases) {
                return str_contains(strtolower($db), 'pos')
                    && !in_array(strtolower($db), $usedDatabases);
            })
            ->values();

        return view('admin.pricing.index', compact(
            'pricings',
            'search',
            'databases'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $pricing = Pricing::findOrFail($id);

        $request->validate([
            'status'      => 'required|in:aktif,nonaktif,waiting approval',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'db_database' => 'required_if:status,aktif',
            'db_username' => 'required_if:status,aktif',
            'db_password' => 'nullable', // Boleh kosong jika tidak ingin ganti password
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ UPDATE PRICING
            $pricing->update([
                'status'     => $request->status,
                'start_date' => $request->status === 'aktif' ? $request->start_date : null,
                'end_date'   => $request->status === 'aktif' ? $request->end_date : null,
            ]);

            if ($request->status !== 'aktif') {
                DB::commit();
                return back()->with('success', 'Status berhasil diperbarui.');
            }

            // 2️⃣ USER UTAMA
            $mainUser = DB::table('users')->where('email', $pricing->email)->first();

            if (!$mainUser) {
                throw new \Exception('User utama tidak ditemukan');
            }

            // Siapkan password terenkripsi jika ada input baru
            $encryptedPassword = Crypt::encryptString($request->db_password);

            DB::table('users')
                ->where('id', $mainUser->id)
                ->update([
                    'db_host'     => $request->db_host ?? '127.0.0.1',
                    'db_database' => $request->db_database,
                    'db_username' => $request->db_username,
                    'db_password' => $encryptedPassword, // ✅ MENGGUNAKAN CRYPT
                    'updated_at'  => now(),
                ]);

            // 3️⃣ USER DB_POS
            $dbPos = DB::connection('db_pos');
            $dbPosUser = $dbPos->table('users')->where('email', $mainUser->email)->first();

            $userData = [
                'name'            => $mainUser->name,
                'password'        => $mainUser->password, // Ini password LOGIN (sudah di-hash)
                'is_active'       => 1,
                'tenant_host'     => $request->db_host ?? '127.0.0.1',
                'tenant_port'     => '3306',
                'tenant_database' => $request->db_database,
                'tenant_username' => $request->db_username,
                'tenant_password' => $encryptedPassword, // ✅ MENGGUNAKAN CRYPT
                'valid_date'      => $request->end_date,
                'updated_at'      => now(),
            ];

            if ($dbPosUser) {
                $dbPos->table('users')->where('id', $dbPosUser->id)->update($userData);
                $dbPosUserId = $dbPosUser->id;
            } else {
                $userData['email'] = $mainUser->email;
                $userData['created_at'] = now();
                $dbPosUserId = $dbPos->table('users')->insertGetId($userData);
            }

            // 4️⃣ ROLE (DB_POS)
            $dbPos->table('model_has_roles')->updateOrInsert(
                ['model_id' => $dbPosUserId, 'model_type' => 'App\Models\User'],
                ['role_id' => 1]
            );

            DB::commit();

            Mail::to($pricing->email)->send(new StatusAktifMail($pricing));

            return back()->with('success', 'Status aktif dan database berhasil disinkronkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function renew(Request $request, $id)
    {
        $request->validate([
            'duration'  => 'required|integer|min:1',
            'codepaket' => 'nullable|exists:packages,id', // tambahkan validasi paket
        ]);

        $pricing = Pricing::findOrFail($id);

        if (strtolower($pricing->status) !== 'aktif') {
            return back()->with('error', 'Hanya langganan aktif yang dapat diperpanjang.');
        }

        // Simpan data lama sebelum diperpanjang
        $oldEndDate = $pricing->end_date ?? now();
        $oldPackage = $pricing->codepaket; // simpan paket lama untuk referensi di riwayat

        // Tambah masa aktif sesuai pilihan
        $months = (int) $request->duration;
        $days = $this->monthToDays($months);

        if ($pricing->end_date) {
            $pricing->end_date = $pricing->end_date->addDays($days);
        } else {
            $pricing->start_date = now();
            $pricing->end_date = now()->addDays($days);
        }


        $pricing->save();

        // === Simpan ke tabel renewals ===
        \App\Models\Renewal::create([
            'pricing_id'   => $pricing->id,
            'duration'     => $days,
            'old_end_date' => $oldEndDate,
            'new_end_date' => $pricing->end_date,
            'approved_by'  => auth()->user()->email ?? 'Admin',
            'old_package'  => $oldPackage,
            'new_package'  => $pricing->codepaket,
        ]);

        // === Kirim email ke user ===
        if ($pricing->email) {
            Mail::to($pricing->email)->send(new \App\Mail\RenewalNotification($pricing, $months));
        }

        return back()->with('success', 'Masa aktif diperpanjang ' . $days  . ' hari dan email telah dikirim.');
    }

    public function showPayment(Request $request, $id)
    {
        $pricing = Pricing::findOrFail($id);
        $packageId = $request->query('package_id');
        $duration = (int) $request->query('duration', 1);

        $package = \App\Models\Package::find($packageId);
        $totalBayar = $package ? $package->price * $duration : 0;

        return view('pricing.payment', compact('pricing', 'package', 'duration', 'totalBayar'));
    }


    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $pricing = Pricing::findOrFail($id);
        $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

        $pricing->bukti_transfer = $path;
        $pricing->save();

        return back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    public function proceedPayment(Request $request, $id)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'duration'   => 'required|integer|min:1',
        ]);

        $pricing = \App\Models\Pricing::findOrFail($id);
        $newPackage = \App\Models\Package::findOrFail($request->package_id);

        $durationDays = $this->monthToDays($request->duration);
        $now = now();

        // ============================
        // DATA PAKET LAMA
        // ============================
        $oldPackagePrice = $pricing->harga_paket; // harga paket lama
        $oldEndDate      = $pricing->end_date;

        // 🚫 TOLAK DOWNGRADE
        if ($newPackage->price < $oldPackagePrice) {
            return back()->with('error', 'Downgrade paket tidak diperbolehkan.');
        }

        // ============================
        // TOTAL NORMAL PAKET BARU
        // ============================
        $newTotalPrice = $newPackage->price * $request->duration;

        // ============================
        // CEK: PERPANJANGAN / UPGRADE
        // ============================
        $isUpgrade = $newPackage->price > $oldPackagePrice;

        if ($isUpgrade) {
            // ============================
            // 🔼 UPGRADE PAKET
            // ============================

            // Hitung sisa hari paket lama
            $remainingDays = 0;
            if ($oldEndDate && $oldEndDate->isFuture()) {
                $remainingDays = $now->diffInDays($oldEndDate);
            }

            // Harga per hari paket lama
            $oldDailyPrice = $oldPackagePrice / 30;

            // Nilai sisa paket lama
            $remainingValue = $remainingDays * $oldDailyPrice;

            // Total bayar setelah dikurangi sisa
            $finalTotal = max(0, $newTotalPrice - $remainingValue);

            $newEndDate = $now->copy()->addDays($durationDays);
        } else {
            // ============================
            // 🔁 PERPANJANGAN BIASA
            // ============================
            $finalTotal = $newTotalPrice;

            $newEndDate = $oldEndDate
                ? $oldEndDate->copy()->addDays($durationDays)
                : $now->copy()->addDays($durationDays);
        }

        // ============================
        // SIMPAN RENEWAL
        // ============================
        $renewal = \App\Models\Renewal::create([
            'pricing_id'   => $pricing->id,
            'old_package'  => $pricing->codepaket,
            'new_package'  => $newPackage->id,
            'duration'     => $request->duration,
            'total_price'  => round($finalTotal),
            'status'       => 'waiting approval',
            'old_end_date' => $oldEndDate,
            'new_end_date' => $newEndDate,
            'approved_by'  => null,
        ]);

        // ============================
        // UPDATE PRICING
        // ============================
        $pricing->update([
            'status'          => 'waiting approval',
            'bukti_transfer'  => null,
        ]);

        // ============================
        // 📧 KIRIM EMAIL INVOICE
        // ============================
        Mail::to($pricing->user->email)
            ->send(new InvoicePaymentMail($renewal));

        return redirect()
            ->route('pricing.paymentPage', ['renewal' => $renewal->id])
            ->with('success', 'Invoice telah dikirim ke email. Silakan lakukan pembayaran.');
    }

    public function paymentPage($renewalId)
    {
        $renewal = \App\Models\Renewal::with(['package', 'pricing'])->findOrFail($renewalId);
        return view('pricing.payment', compact('renewal'));
    }



    public function uploadProof(Request $request, $renewalId)
    {
        $request->validate([
            'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $renewal = Renewal::findOrFail($renewalId);

        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti-transfer', 'public');
            $renewal->bukti_transfer = $path;
            $renewal->status = 'waiting approval';
            $renewal->save();

            $pricing = \App\Models\Pricing::where('id', $renewal->pricing_id)
                ->latest()
                ->first();

            if ($pricing) {
                $pricing->bukti_transfer = $path;
                $pricing->status = 'Waiting Approval';
                $pricing->save();
            }

            // Ambil semua email admin
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();

            if (!empty($adminEmails)) {
                // Kirim email ke semua admin — gunakan $renewal, bukan $pricing
                Mail::to($adminEmails)
                    ->send(new BuktiTransferUploadedMail($renewal, 'renewal'));
            }
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function activateRenewal($id)
    {
        $renewal = \App\Models\Renewal::findOrFail($id);
        $pricing = \App\Models\Pricing::findOrFail($renewal->pricing_id);
        $package = Package::find($renewal->new_package);

        // Update data pricing sesuai renewal
        $pricing->end_date = $renewal->new_end_date;
        $pricing->codepaket = $renewal->new_package ?? $pricing->codepaket;
        $pricing->namapaket = $package->name;
        $pricing->harga_paket = $package->price;
        $pricing->durasi = $renewal->duration;
        $pricing->status = 'Aktif';
        $pricing->save();

        // Update renewal status jadi aktif
        $renewal->status = 'Aktif';
        $renewal->approved_by = auth()->user()->email ?? 'Admin';
        $renewal->save();

        // Tambah masa aktif sesuai pilihan
        $days = (int) $renewal->duration;
        // === Kirim email ke user ===
        if ($pricing->email) {
            Mail::to($pricing->email)->send(new \App\Mail\RenewalNotification($renewal, $days));
        }

        return back()->with('success', 'Perpanjangan berhasil diaktifkan.');
    }

    private function monthToDays(int $month): int
    {
        return $month * 30;
    }

    public function previewPrice(Request $request, $id)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'duration'   => 'required|integer|min:1',
        ]);

        $pricing        = Pricing::findOrFail($id);
        $newPackage     = Package::findOrFail($request->package_id);
        $currentPackage = Package::find($pricing->codepaket);

        $durationMonths = (int) $request->duration;
        $durationDays   = $durationMonths * 30;

        $now       = now();
        $oldEnd    = $pricing->end_date;
        $oldPrice  = $currentPackage?->price ?? 0;
        $newPrice  = $newPackage->price;

        /* ======================================================
     | 🚫 DOWNGRADE (DITOLAK)
     ====================================================== */
        if ($newPrice < $oldPrice) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Downgrade paket tidak diperbolehkan',
            ]);
        }

        /* ======================================================
     | HITUNG DASAR
     ====================================================== */
        $normalTotal = $newPrice * $durationMonths;

        $remainingDays = 0;
        $remainingValue = 0;

        /* ======================================================
     | 🔼 UPGRADE
     ====================================================== */
        if ($newPrice > $oldPrice) {

            if ($oldEnd && $oldEnd->isFuture()) {
                $remainingDays = $now->diffInDays($oldEnd);
                $oldPerDay     = $oldPrice / 30;
                $remainingValue = round($remainingDays * $oldPerDay);
            }

            $totalPay = max($normalTotal - $remainingValue, 0);

            return response()->json([
                'status'           => 'upgrade',
                'new_price'        => $normalTotal,
                'duration_days'    => $durationDays,
                'remaining_days'   => $remainingDays,
                'remaining_value'  => $remainingValue,
                'total'            => $totalPay,
                'newEndDate'       => $now->copy()->addDays($durationDays)->format('d M Y'),
            ]);
        }

        /* ======================================================
     | 🔁 RENEW (PAKET SAMA)
     ====================================================== */
        $newEndDate = $oldEnd && $oldEnd->isFuture()
            ? $oldEnd->copy()->addDays($durationDays)
            : $now->copy()->addDays($durationDays);

        return response()->json([
            'status'           => 'renew',
            'new_price'        => $normalTotal,
            'duration_days'    => $durationDays,
            'remaining_days'   => 0,
            'remaining_value'  => 0,
            'total'            => $normalTotal,
            'newEndDate'       => $newEndDate->format('d M Y'),
        ]);
    }
}
