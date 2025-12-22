<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use Illuminate\Http\Request;
use App\Models\MembershipUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MembershipController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pricing_id'   => 'required|exists:pricings,id',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email',
            'userpassword' => 'required|string',
            'level'        => 'required|in:admin,kasir',
        ]);

        // ❌ CEK EMAIL SUDAH ADA DI USERS UTAMA
        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email sudah terdaftar sebagai user utama.');
        }

        // ❌ CEK EMAIL SUDAH ADA DI MEMBERSHIP
        if (MembershipUser::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email sudah digunakan sebagai membership.');
        }

        $authUser = Auth::user();
        $pricing  = Pricing::findOrFail($request->pricing_id);

        DB::beginTransaction();

        try {
            // =========================
            // 1️⃣ SIMPAN KE DB UTAMA
            // =========================
            $membershipUser = MembershipUser::create([
                'pricing_id'   => $pricing->id,
                'name'         => $request->name,
                'email'        => $request->email,
                'level'        => $request->level,
                'userpassword' => Hash::make($request->userpassword),
                'db_database'  => $authUser->db_database,
                'db_host'      => $authUser->db_host,
                'db_port'      => $authUser->db_port,
                'db_username'  => $authUser->db_username,
                'db_password'  => $authUser->db_password,
            ]);

            // =========================
            // 2️⃣ SIMPAN KE DB_POS.users
            // (ID AUTO INCREMENT)
            // =========================
            $posUserId = DB::connection('db_pos')
                ->table('users')
                ->insertGetId([
                    'name'             => $request->name,
                    'email'            => $request->email,
                    'password'         => Hash::make($request->userpassword),
                    'is_active'        => 1,
                    'tenant_database'  => $authUser->db_database,
                    'tenant_host'      => $authUser->db_host,
                    'tenant_port'      => $authUser->db_port,
                    'tenant_username'  => $authUser->db_username,
                    'tenant_password'  => $authUser->db_password,
                    'valid_date'       => optional($pricing->end_date)?->format('Y-m-d'),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

            // =========================
            // 3️⃣ SIMPAN ROLE (PAKAI ID USER DB_POS)
            // =========================
            DB::connection('db_pos')
                ->table('model_has_roles')
                ->insert([
                    'role_id'    => $request->level === 'admin' ? 1 : 3,
                    'model_type' => 'App\Models\User',
                    'model_id'   => $posUserId,
                ]);

            DB::commit();

            return back()->with('success', 'Membership + User POS berhasil ditambahkan');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'level' => 'required|in:admin,kasir',
        ]);

        $membershipUser = MembershipUser::findOrFail($id);

        DB::beginTransaction();

        try {
            // =========================
            // 1️⃣ UPDATE DB UTAMA
            // =========================
            $membershipUser->update([
                'name'  => $request->name,
                'level' => $request->level,
            ]);

            // =========================
            // 2️⃣ AMBIL USER DB_POS BERDASARKAN EMAIL
            // =========================
            $posUser = DB::connection('db_pos')
                ->table('users')
                ->where('email', $membershipUser->email)
                ->first();

            if ($posUser) {
                // =========================
                // 3️⃣ UPDATE DB_POS.users
                // =========================
                DB::connection('db_pos')->table('users')
                    ->where('id', $posUser->id)
                    ->update([
                        'name'       => $request->name,
                        'updated_at' => now(),
                    ]);

                // =========================
                // 4️⃣ UPDATE ROLE
                // =========================
                $roleId = $request->level === 'admin' ? 1 : 3;

                DB::connection('db_pos')->table('model_has_roles')
                    ->where('model_id', $posUser->id)
                    ->where('model_type', 'App\Models\User')
                    ->update([
                        'role_id' => $roleId,
                    ]);
            }

            DB::commit();

            return back()->with('success', 'User membership berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update user: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $member = MembershipUser::findOrFail($id);

            // =========================
            // 1️⃣ AMBIL USER DB_POS
            // =========================
            $posUser = DB::connection('db_pos')
                ->table('users')
                ->where('email', $member->email)
                ->first();

            if ($posUser) {
                // =========================
                // 2️⃣ HAPUS ROLE
                // =========================
                DB::connection('db_pos')->table('model_has_roles')
                    ->where('model_id', $posUser->id)
                    ->where('model_type', 'App\Models\User')
                    ->delete();

                // =========================
                // 3️⃣ HAPUS USER DB_POS
                // =========================
                DB::connection('db_pos')->table('users')
                    ->where('id', $posUser->id)
                    ->delete();
            }

            // =========================
            // 4️⃣ HAPUS MEMBERSHIP USER
            // =========================
            $member->delete();

            DB::commit();

            return back()->with('success', 'User membership + POS berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus user: ' . $e->getMessage());
        }
    }
}
