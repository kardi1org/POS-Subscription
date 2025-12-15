<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pricing_id' => 'required|exists:pricings,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'userpassword' => 'required|string|min:4',
            'level' => 'required|in:admin,kasir',
        ]);

        // 🟡 Cek manual kalau email sudah dipakai (optional)
        if (MembershipUser::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email sudah digunakan.');
        }

        $user = Auth::user();

        MembershipUser::create([
            'pricing_id' => $request->pricing_id,
            'name' => $request->name,
            'email' => $request->email,
            'userpassword' => bcrypt($request->userpassword),
            'level' => $request->level,
            'db_database' => $user->db_database,
            'db_host' => $user->db_host,
            'db_port' => $user->db_port,
            'db_username' => $user->db_username,
            'db_password' => $user->db_password,
        ]);

        return back()->with('success', 'User membership berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:admin,kasir',
        ]);

        $user = MembershipUser::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'level' => $request->level,
        ]);

        // Coba koneksi ke database tenant
        try {
            config([
                'database.connections.tenant' => [
                    'driver' => 'mysql',
                    'host' => $user->db_host,
                    'port' => $user->db_port,
                    'database' => $user->db_database,
                    'username' => $user->db_username,
                    'password' => $user->db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ],
            ]);

            // Update tabel users di database tenant
            DB::connection('tenant')->table('users')
                ->where('email', $user->email)
                ->update([
                    'name' => $request->name,
                    'updated_at' => now(),
                ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Update membership berhasil, tetapi gagal update di tenant: ' . $e->getMessage());
        }

        return back()->with('success', 'Data user membership berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = MembershipUser::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User membership berhasil dihapus.');
    }
}
