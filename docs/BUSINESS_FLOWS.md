# Business Flows

Dokumen ini memetakan alur bisnis utama dari entry point sampai perubahan database berdasarkan kode repository.

## 1. Registrasi User

Titik awal:

- Route auth dari `Auth::routes()` di `routes/web.php`.
- Controller: `App\Http\Controllers\Auth\RegisterController`.

Alur:

1. `RegisterController::validator` memvalidasi `name`, `email`, `password`.
2. `RegisterController::create` membuat row `users`.
3. `User` implements `MustVerifyEmail`.
4. `User::sendEmailVerificationNotification` mengirim `CustomVerifyEmail`.

Tabel ditulis:

- `users`: `name`, `email`, `password`.

Error handling:

- Menggunakan mekanisme Laravel auth/validation.

## 2. Login Dan Redirect Role

Titik awal:

- Route auth dari `Auth::routes()`.
- Controller: `App\Http\Controllers\Auth\LoginController`.

Alur:

1. Login memakai trait `AuthenticatesUsers`.
2. `LoginController::authenticated` memeriksa `user->role`.
3. Jika `role === 'admin'`, redirect ke route `admin.pricing.index`.
4. Selain itu redirect ke `/home`.

Tabel dibaca:

- `users`: `email`, `password`, `role`.

## 3. Landing Page Paket

Titik awal:

- `GET /`
- Controller: `LandingController@index`.

Alur:

1. Mengambil semua row `Package::all()`.
2. Mengirim data ke `resources/views/landing.blade.php`.

Tabel dibaca:

- `packages`: `id`, `name`, `price`.

## 4. Signup Paket Subscription

Titik awal:

- `POST /pricing/store`
- Controller: `HomeController@store`.

Input:

- `email`
- `codepaket`
- `harga_paket`
- `durasi`
- optional `notes`

Validasi:

- `email`: required email.
- `codepaket`: required exists `packages.id`.
- `harga_paket`: numeric.
- `durasi`: integer min 1.

Algoritma:

1. Ambil `Package::findOrFail($request->codepaket)`.
2. Hitung `totalPrice = harga_paket * durasi`.
3. Hitung `durationDays = durasi * 30`.
4. Buat `Pricing` dengan status `Pending`.
5. Kirim `InvoiceSignupMail`.
6. Redirect ke halaman transfer.

Tabel dibaca:

- `packages`: `id`, `name`, `price`.

Tabel ditulis:

- `pricings`: `email`, `codepaket`, `namapaket`, `harga_paket`, `durasi`, `notes`, `status`.

Status:

- Sesudah proses: `pricings.status = Pending`.

Error handling:

- Validation exception dari Laravel.
- `findOrFail` menghasilkan 404 jika package tidak ada.
- Tidak ada transaction eksplisit.

## 5. Upload Bukti Transfer Signup

Titik awal:

- `POST /pricings/{pricing}/upload-bukti`
- Controller: `PricingController@uploadBukti`.

Input:

- File `bukti`.

Validasi:

- Required file.
- Mime: `jpg`, `jpeg`, `png`, `pdf`.
- Max: 2048 KB.

Algoritma:

1. Ambil `Pricing::findOrFail($id)`.
2. Jika `pricings.bukti_transfer` lama ada di disk public, hapus.
3. Simpan file baru ke `bukti-transfer` pada disk `public`.
4. Update `pricings.bukti_transfer`.
5. Update `pricings.status = Waiting Approval`.
6. Cari renewal terakhir untuk pricing tersebut.
7. Jika ada renewal, hapus bukti lama renewal dan update `renewals.bukti_transfer`, `renewals.status`.
8. Ambil email admin dari `users.role = admin`.
9. Kirim `BuktiTransferUploadedMail`.

Tabel dibaca:

- `pricings`
- `renewals`
- `users`: `role`, `email`

Tabel ditulis:

- `pricings`: `bukti_transfer`, `status`.
- `renewals`: `bukti_transfer`, `status` jika renewal ditemukan.

Status:

- Pricing menjadi `Waiting Approval`.
- Renewal terakhir bisa menjadi `Waiting Approval`.

Error handling:

- Validation dan `findOrFail`.
- Tidak ada transaction eksplisit; file bisa sudah tersimpan ketika update/email gagal.

## 6. Admin Melihat Pricing

Titik awal:

- `GET /admin/pricings`
- Middleware: `auth`, `admin`.
- Controller: `PricingController@index`.

Input:

- Optional query `search`.

Algoritma:

1. Query `pricings` dengan filter `namapaket`, `email`, atau `status`.
2. Paginate 10.
3. Ambil daftar `users.db_database` yang sudah dipakai.
4. Jalankan raw SQL `SHOW DATABASES`.
5. Filter database yang mengandung `_pos` dan belum dipakai.
6. Render `resources/views/admin/pricing/index.blade.php`.

Tabel dibaca:

- `pricings`
- `users.db_database`
- metadata database MySQL dari `SHOW DATABASES`.

## 7. Admin Update Status Dan Sinkronisasi POS

Titik awal:

- `PUT /admin/admin/pricing/{id}/update-status`
- Controller: `PricingController@updateStatus`.

Input:

- `status`
- `start_date`
- `end_date`
- `db_database`
- `db_username`
- `db_password`
- optional `db_host`

Validasi:

- `status`: `aktif`, `nonaktif`, `waiting approval`.
- `end_date`: after or equal `start_date`.
- `db_database`, `db_username`: required jika status `aktif`.

Algoritma:

1. Ambil `Pricing::findOrFail($id)`.
2. Mulai transaction pada default connection.
3. Update `pricings.status`, `start_date`, `end_date`.
4. Jika status bukan `aktif`, commit dan selesai.
5. Ambil user utama dari `users.email = pricings.email`.
6. Encrypt `db_password` dengan `Crypt::encryptString`.
7. Update credential POS di `users` lokal.
8. Ambil/insert user di `db_pos.users`.
9. Set tenant credential, `valid_date`, `codepaket`, `level`.
10. Buat/update default outlet di `db_pos.outlets`.
11. Buat/update pivot `db_pos.outlet_user`.
12. Buat/update role di `db_pos.model_has_roles`.
13. Commit default DB transaction.
14. Kirim `StatusAktifMail`.

Tabel lokal dibaca/ditulis:

- `pricings`: `status`, `start_date`, `end_date`.
- `users`: `email`, `db_host`, `db_port`, `db_database`, `db_username`, `db_password`.

Tabel POS dibaca/ditulis:

- `db_pos.users`
- `db_pos.outlets`
- `db_pos.outlet_user`
- `db_pos.model_has_roles`

Status:

- Bisa menjadi `aktif`, `nonaktif`, atau `waiting approval`.

Error handling:

- `try/catch`.
- `DB::rollBack()` hanya untuk default connection.
- Operasi `db_pos` tidak dibungkus transaction eksplisit pada koneksi `db_pos`.

## 8. Preview Harga Renewal/Upgrade

Titik awal:

- Inline JS di `resources/views/home.blade.php`.
- `POST /pricing/{id}/preview`.
- Controller: `PricingController@previewPrice`.

Input:

- `package_id`
- `duration`

Validasi:

- `package_id` exists `packages.id`.
- `duration` integer min 1.

Algoritma:

1. Ambil pricing, package baru, dan package saat ini.
2. Hitung `durationDays = durationMonths * 30`.
3. Jika harga baru lebih rendah dari harga lama, return JSON `status = error`.
4. Hitung `normalTotal = newPrice * durationMonths`.
5. Jika upgrade:
   - Hitung sisa hari dari `pricing.end_date`.
   - Hitung `oldPerDay = oldPrice / 30`.
   - Hitung `remainingValue = remainingDays * oldPerDay`.
   - Total bayar `max(normalTotal - remainingValue, 0)`.
   - New end date dari sekarang + durationDays.
6. Jika renewal paket sama:
   - New end date dari end date lama jika masih future, atau dari sekarang.
   - Total bayar `normalTotal`.

Tabel dibaca:

- `pricings`: `codepaket`, `end_date`.
- `packages`: `id`, `price`.

Response:

- JSON dengan `status`, `new_price`, `duration_days`, `remaining_days`, `remaining_value`, `total`, `newEndDate`.

## 9. Membuat Renewal/Upgrade Berbayar

Titik awal:

- `POST /pricing/{id}/proceed-payment`
- Controller: `PricingController@proceedPayment`.

Input:

- `package_id`
- `duration`

Validasi:

- `package_id` exists `packages.id`.
- `duration` integer min 1.

Algoritma:

1. Ambil pricing dan package baru.
2. Hitung `durationDays = monthToDays(duration)`.
3. Ambil harga paket lama dari `pricings.harga_paket`.
4. Tolak downgrade jika harga package baru lebih rendah.
5. Hitung total normal `newPackage.price * duration`.
6. Jika upgrade, kurangi nilai sisa paket lama.
7. Jika renewal paket sama, pakai total normal.
8. Buat row `renewals` dengan status `waiting approval`.
9. Update `pricings.status = waiting approval`, `pricings.bukti_transfer = null`.
10. Kirim `InvoicePaymentMail`.
11. Redirect ke halaman payment renewal.

Tabel dibaca:

- `pricings`
- `packages`

Tabel ditulis:

- `renewals`: `pricing_id`, `old_package`, `new_package`, `duration`, `total_price`, `status`, `old_end_date`, `new_end_date`, `approved_by`.
- `pricings`: `status`, `bukti_transfer`.

Status:

- Renewal baru: `waiting approval`.
- Pricing: `waiting approval`.

Error handling:

- Validation dan `findOrFail`.
- Tidak ada transaction eksplisit.

## 10. Upload Bukti Renewal

Titik awal:

- `POST /pricing/payment/{renewal}/upload`
- Controller: `PricingController@uploadProof`.

Input:

- File `bukti`.

Validasi:

- Required file.
- Mime: `jpg`, `jpeg`, `png`, `pdf`.
- Max: 2048 KB.

Algoritma:

1. Ambil `Renewal::findOrFail($renewalId)`.
2. Simpan file ke disk public.
3. Update `renewals.bukti_transfer` dan `renewals.status = waiting approval`.
4. Ambil pricing terkait dan update `pricings.bukti_transfer`, `pricings.status = Waiting Approval`.
5. Kirim email ke admin.

Tabel dibaca/ditulis:

- `renewals`
- `pricings`
- `users` admin

Status:

- Renewal: `waiting approval`.
- Pricing: `Waiting Approval`.

## 11. Admin Aktivasi Renewal

Titik awal:

- `POST /admin/pricing/activate-renewal/{id}`
- Controller: `PricingController@activateRenewal`.

Algoritma:

1. Ambil `Renewal`, `Pricing`, dan `Package`.
2. Update `pricings.end_date`, `codepaket`, `namapaket`, `harga_paket`, `durasi`, `status = Aktif`.
3. Update `renewals.status = Aktif`, `approved_by`.
4. Ambil email pricing dan semua email membership pada `membership_users.pricing_id`.
5. Update `db_pos.users.valid_date` dan `codepaket` untuk email-email tersebut.
6. Kirim `RenewalNotification`.

Tabel lokal dibaca/ditulis:

- `renewals`
- `pricings`
- `packages`
- `membership_users`

Tabel POS ditulis:

- `db_pos.users`: `valid_date`, `codepaket`, `updated_at`.

Error handling:

- Sinkronisasi POS dibungkus `try/catch` terpisah dan error dicatat ke log.
- Update lokal dilakukan tanpa transaction eksplisit.

## 12. Membership User

Titik awal:

- `POST /membership/store`
- `POST /membership/update/{id}`
- `DELETE /membership/delete/{id}`
- Controller: `MembershipController`.

Store input:

- `pricing_id`, `name`, `email`, `userpassword`, `level`, optional `outlet_ids`.

Store validasi:

- `pricing_id` exists `pricings.id`.
- `level` in `manager,kasir`.
- Email tidak boleh ada di `users` utama atau `membership_users`.

Store menulis:

- Lokal `membership_users`.
- POS `users`.
- POS `model_has_roles`.
- POS `outlet_user` jika ada outlet.

Update menulis:

- Lokal `membership_users.name`, `level`.
- POS `users.name`, `level`.
- POS `model_has_roles.role_id`.
- POS `outlet_user` dihapus dan dibuat ulang jika request punya `outlet_ids`.

Destroy menulis:

- POS `model_has_roles` delete.
- POS `users` delete.
- Lokal `membership_users` delete.

Error handling:

- `DB::beginTransaction()` default connection.
- Operasi `db_pos` tidak memakai transaction eksplisit pada koneksi POS.

## 13. Outlet

Titik awal:

- `POST /outlet/store`
- `PUT /outlet/{id}`
- `DELETE /outlet/{id}`
- Controller: `OutletController`.

Store:

- Validasi `pricing_id`, `name`, `address`, optional `telp`, `info`.
- Ambil `Pricing`.
- Insert ke `db_pos.outlets`.

Update:

- Validasi `name`, `address`, optional `telp`, `info`.
- Update `db_pos.outlets`.

Destroy:

- Cek `db_pos.outlet_user` apakah outlet dipakai.
- Cek jumlah outlet untuk email pricing minimal lebih dari 1.
- Delete `db_pos.outlets`.

Catatan:

- Controller memakai `$request->phone` untuk mengisi field `telp`, sementara validasi memakai input `telp`.

## 14. Scheduler Reminder

Titik awal:

- `app/Console/Kernel.php`
- Command `pricing:send-reminders`.

Algoritma:

1. Ambil `Pricing` status `Aktif` dan `end_date` not null.
2. Hitung sisa hari dengan `diffInDays(..., false)`.
3. Jika 0 sampai 7 hari dan belum dikirim hari ini, kirim `RenewalReminderMail`.
4. Update `pricings.reminder_sent_at`.

Tabel dibaca/ditulis:

- `pricings`: `status`, `end_date`, `reminder_sent_at`, `email`.

## 15. Scheduler Update Package

Titik awal:

- `app/Console/Kernel.php`
- Command `pricing:update-package`.

Algoritma:

1. Ambil renewal `status = aktif` dengan `old_end_date < now` dan `new_end_date > now`.
2. Group by `pricing_id`.
3. Ambil renewal terbaru per pricing.
4. Ambil package dari `new_package`.
5. Jika `pricings.codepaket` belum sama, update `codepaket` dan `namapaket`.

Tabel dibaca/ditulis:

- `renewals`
- `packages`
- `pricings`

Catatan:

- Kode mencari status lowercase `aktif`, sedangkan flow aktivasi renewal menyimpan `Aktif`.

