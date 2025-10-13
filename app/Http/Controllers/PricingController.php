<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricing;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusAktifMail;
use App\Mail\RenewalNotification;


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

        // cari data berdasarkan id
        $pricing = Pricing::findOrFail($id);

        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti-transfer', 'public');

            // update field bukti_bayar
            $pricing->bukti_transfer = $path;
            $pricing->save();
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
            'duration' => 'required|integer|min:1',
        ]);

        $pricing = Pricing::findOrFail($id);

        if (strtolower($pricing->status) !== 'aktif') {
            return back()->with('error', 'Hanya langganan aktif yang dapat diperpanjang.');
        }

        // Simpan data lama sebelum diperpanjang
        $oldEndDate = $pricing->end_date ?? now();

        // Tambah masa aktif sesuai pilihan
        $months = (int) $request->duration;

        if ($pricing->end_date) {
            $pricing->end_date = $pricing->end_date->addMonths($months);
        } else {
            $pricing->start_date = now();
            $pricing->end_date = now()->addMonths($months);
        }

        $pricing->save();

        // === Simpan ke tabel renewals ===
        \App\Models\Renewal::create([
            'pricing_id'   => $pricing->id,
            'duration'     => $months,
            'old_end_date' => $oldEndDate,
            'new_end_date' => $pricing->end_date,
            'approved_by'  => auth()->user()->email ?? 'Admin',
        ]);

        // === Kirim email ke user ===
        if ($pricing->email) {
            Mail::to($pricing->email)->send(new RenewalNotification($pricing, $months));
        }

        return back()->with('success', 'Masa aktif diperpanjang ' . $months . ' bulan dan email telah dikirim.');
    }
}
