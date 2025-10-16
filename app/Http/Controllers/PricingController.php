<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricing;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusAktifMail;
use App\Mail\RenewalNotification;
use App\Models\Renewal;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;


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
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }


    public function index(Request $request)
    {
        $search = $request->input('search');

        $pricings = \App\Models\Pricing::when($search, function ($query, $search) {
            return $query->where('namapaket', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pricing.index', compact('pricings', 'search'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pricing = Pricing::findOrFail($id);

        $request->validate([
            'status' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Update status dan masa aktif
        $pricing->status = $request->status;

        if ($request->status === 'aktif') {
            $pricing->start_date = $request->start_date ?? now();
            $pricing->end_date = $request->end_date ?? now()->addMonth();
        } else {
            $pricing->start_date = null;
            $pricing->end_date = null;
        }

        $pricing->save();

        // Kirim email hanya jika status aktif
        if ($pricing->status === 'aktif') {
            Mail::to($pricing->email)->send(new StatusAktifMail($pricing));
        }

        return redirect()->back()->with('success', 'Status dan masa aktif berhasil diperbarui.');
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

        if ($pricing->end_date) {
            $pricing->end_date = $pricing->end_date->addMonths($months);
        } else {
            $pricing->start_date = now();
            $pricing->end_date = now()->addMonths($months);
        }

        // Jika user memilih paket baru (upgrade/downgrade)
        if ($request->filled('codepaket') && $request->codepaket != $pricing->codepaket) {
            $pricing->codepaket = $request->codepaket;
        }

        $pricing->save();

        // === Simpan ke tabel renewals ===
        \App\Models\Renewal::create([
            'pricing_id'   => $pricing->id,
            'duration'     => $months,
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

        return back()->with('success', 'Masa aktif diperpanjang ' . $months . ' bulan dan email telah dikirim.');
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
            'duration' => 'required|integer|min:1',
        ]);

        $pricing = \App\Models\Pricing::findOrFail($id);
        $package = \App\Models\Package::findOrFail($request->package_id);

        // Hitung total harga
        $total = $package->price * $request->duration;

        // Simpan record renewal
        $renewal = \App\Models\Renewal::create([
            'pricing_id'   => $pricing->id,
            'old_package'  => $pricing->codepaket,      // paket lama dari pricing
            'new_package'  => $package->id,      // paket baru yang dipilih user
            'duration'     => $request->duration,
            'total_price'  => $total,
            'status'       => 'waiting approval',
            'old_end_date' => $pricing->end_date,
            'new_end_date' => $pricing->end_date
                ? $pricing->end_date->addMonths($request->duration)
                : now()->addMonths($request->duration),
            'approved_by'  => null,
        ]);


        // Ubah status pricing sementara
        $pricing->status = 'waiting approval';
        $pricing->bukti_transfer = null;
        $pricing->save();

        // Redirect ke halaman konfirmasi pembayaran
        return redirect()->route('pricing.paymentPage', ['renewal' => $renewal->id])
            ->with('success', 'Silakan lakukan pembayaran untuk perpanjangan paket Anda.');
    }



    // public function paymentPage($renewalId)
    // {
    //     $renewal = \App\Models\Renewal::with(['package', 'pricing'])->findOrFail($renewalId);

    //     if (!$renewal->package) {
    //         // fallback supaya tidak error
    //         $package = \App\Models\Package::find($renewal->codepaket);
    //         $renewal->package = $package;
    //     }

    //     return view('pricing.payment', compact('renewal'));
    // }
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
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function activateRenewal($id)
    {
        $renewal = \App\Models\Renewal::findOrFail($id);
        $pricing = \App\Models\Pricing::findOrFail($renewal->pricing_id);

        // Update data pricing sesuai renewal
        $pricing->end_date = $renewal->new_end_date;
        //$pricing->codepaket = $renewal->new_package ?? $pricing->codepaket;
        $pricing->status = 'Aktif';
        $pricing->save();

        // Update renewal status jadi aktif
        $renewal->status = 'Aktif';
        $renewal->approved_by = auth()->user()->email ?? 'Admin';
        $renewal->save();

        return back()->with('success', 'Perpanjangan berhasil diaktifkan.');
    }
}
