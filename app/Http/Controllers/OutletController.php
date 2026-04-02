<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pricing;

class OutletController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pricing_id' => 'required',
            'name'       => 'required|string|max:255',
            'address'    => 'required|string',
        ]);

        $pricing = Pricing::findOrFail($request->pricing_id);
        $dbPos = DB::connection('db_pos');

        try {
            DB::beginTransaction();

            // Insert ke tabel outlets di DB_POS
            $dbPos->table('outlets')->insert([
                'name'       => $request->name,
                'email'      => $pricing->email, // Gunakan email owner sebagai identifier
                'address'    => $request->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Outlet berhasil ditambahkan ke database POS.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal simpan outlet: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        try {
            // Update langsung ke koneksi DB POS
            DB::connection('db_pos')->table('outlets')
                ->where('id', $id)
                ->update([
                    'name'       => $request->name,
                    'address'    => $request->address,
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Data outlet berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update outlet: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $dbPos = DB::connection('db_pos');

            // 1. Cek apakah outlet ini terdaftar di tabel outlet_user
            $isUsed = $dbPos->table('outlet_user')->where('outlet_id', $id)->exists();

            if ($isUsed) {
                return back()->with('error', 'Gagal hapus! Outlet ini masih terhubung dengan user di tabel outlet_user. Hapus akses user terlebih dahulu.');
            }

            // 2. Cek minimal 1 outlet (seperti aturan sebelumnya)
            $pricing = Pricing::findOrFail($request->pricing_id);
            $outletCount = $dbPos->table('outlets')->where('email', $pricing->email)->count();

            if ($outletCount <= 1) {
                return back()->with('error', 'Gagal hapus! Minimal harus ada satu outlet utama.');
            }

            // 3. Jalankan hapus
            $dbPos->table('outlets')->where('id', $id)->delete();

            return back()->with('success', 'Outlet berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
