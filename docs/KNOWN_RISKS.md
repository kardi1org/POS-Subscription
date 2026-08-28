# Known Risks

Dokumen ini mencatat technical debt dan area sensitif berdasarkan pemeriksaan repository.

## Risiko Schema Dan Status

### Status uppercase/lowercase tidak konsisten

Sumber:

- Migration `pricings.status`: `Pending`, `Active`, `Nonaktif`, `Waiting Approval`, `Aktif`.
- `PricingController@updateStatus`: validasi `aktif`, `nonaktif`, `waiting approval`.
- `PricingController@proceedPayment`: set `waiting approval`.
- `PricingController@uploadProof`: set renewal `waiting approval` dan pricing `Waiting Approval`.
- `PricingController@activateRenewal`: set `Aktif`.
- `UpdatePricingPackage`: query renewal `status = aktif`.

Dampak:

- Query bisa tidak menemukan data.
- Insert/update ke enum bisa gagal jika nilai tidak cocok dengan enum database.
- UI bisa menampilkan status berbeda untuk kondisi yang sama.

### `renewals.duration` ambigu

Sumber:

- `PricingController@renew` menyimpan `duration` sebagai hari.
- `PricingController@proceedPayment` menyimpan `duration` dari request bulan.
- View `pricing/payment.blade.php` menampilkan `duration * 30`.
- View admin juga menampilkan beberapa nilai duration dikali 30.

Dampak:

- Invoice dan masa aktif bisa salah.
- Renewal history bisa membingungkan.

### `MembershipController` level tidak sesuai enum migration

Sumber:

- Migration `membership_users.level`: enum `admin`, `kasir`.
- `MembershipController@store/update`: validasi `manager`, `kasir`.

Dampak:

- Insert/update level `manager` bisa gagal pada database yang menerapkan enum migration.

### Relasi `Renewal::user()` kemungkinan salah

Sumber:

- `Renewal::user()` memakai hasOneThrough dengan foreign key `user_id` di `Pricing`.
- Schema `pricings` tidak memiliki `user_id`.
- `Pricing::user()` memakai relasi lewat `email`.

Dampak:

- Pemanggilan `Renewal::user()` dapat menghasilkan query salah atau data kosong.

## Risiko Integrasi `db_pos`

### Transaction lintas database tidak atomic

Sumber:

- `PricingController@updateStatus`
- `MembershipController@store/update/destroy`
- `OutletController@store`

Kode membuka `DB::beginTransaction()` pada default connection, tetapi melakukan operasi pada `DB::connection('db_pos')`.

Dampak:

- Jika operasi default berhasil tetapi `db_pos` gagal, data bisa tidak sinkron.
- Rollback default connection tidak otomatis rollback koneksi `db_pos`.

### Tabel POS belum sepenuhnya cocok dengan dump

Sumber:

- Kode memakai `db_pos.outlets` dan `db_pos.outlet_user`.
- `pos_subs_new.sql` yang diperiksa tidak memuat `CREATE TABLE outlets` atau `CREATE TABLE outlet_user`.
- Kode menulis `db_pos.users.codepaket` dan `db_pos.users.level`, tetapi field tersebut tidak terlihat di `CREATE TABLE users` pada dump POS.

Dampak:

- Flow aktivasi, membership, dan outlet bisa gagal di environment dengan schema POS sesuai dump.

### Raw SQL `SHOW DATABASES`

Sumber:

- `PricingController@index`.

Dampak:

- Membutuhkan privilege database tertentu.
- Coupling ke MySQL.
- Dapat gagal di hosting yang membatasi metadata database.

## Risiko Authorization

### Route membership/outlet tidak berada dalam group auth eksplisit

Sumber:

- `routes/web.php` mendefinisikan route membership/outlet di luar group `auth`.
- Controller `MembershipController` memakai `Auth::user()` dalam `store`.
- `OutletController` tidak memiliki constructor auth.

Dampak:

- Perlu verifikasi middleware web/auth aktual; route dapat rentan jika tidak dilindungi di level lain.
- Request tanpa auth bisa menyebabkan error atau akses tidak semestinya.

### Admin memakai `role`, bukan `is_admin`

Sumber:

- `AdminMiddleware` memeriksa `Auth::user()->role === 'admin'`.
- Tabel `users` masih memiliki `is_admin`.

Dampak:

- Field `is_admin` bisa menyesatkan developer.
- Data role harus konsisten.

## Risiko File Upload

Sumber:

- `PricingController@uploadBukti`.
- `PricingController@uploadProof`.

Risiko:

- File disimpan sebelum semua update/email selesai.
- `uploadProof` tidak menghapus file renewal lama sebelum menyimpan file baru.
- Penyimpanan path bukti di pricing dan renewal dapat menyebabkan coupling antar transaksi.

## Risiko View Dan N+1

Sumber:

- `resources/views/home.blade.php`
- `resources/views/admin/pricing/index.blade.php`

Temuan:

- View melakukan query `Renewal::where`, `Package::find`, `MembershipUser::where`, `DB::connection('db_pos')`.
- Query berada di loop pricing.

Dampak:

- N+1 query.
- Logika bisnis tersebar di view.
- Perubahan controller dapat tidak cukup untuk mengubah behavior.

## Risiko Migration Rollback

Migration dengan `down()` kosong atau tidak membalik perubahan:

- `2025_09_26_015924_add_bukti_transfer_to_pricings_table.php`
- `2025_10_14_035544_add_package_columns_to_renewals_table.php`
- `2025_11_03_085318_add_db_credentials_to_membership_users_table.php`
- `2025_12_22_104236_change_password_columns_to_text.php`
- `2026_01_09_093240_update_price_on_packages_table.php`

Dampak:

- Rollback migration tidak dapat mengembalikan schema secara bersih.

## Risiko Kode Mati / Tidak Lengkap

### `RenewalLogController.phpxx`

Sumber:

- File: `app/Http/Controllers/RenewalLogController.phpxx`.
- Route mengimport `RenewalLogController` di `routes/web.php`.
- File berekstensi `.phpxx`, bukan `.php`.
- Kode di dalamnya merujuk model `RenewalLog`, tetapi model tersebut tidak ditemukan.

Dampak:

- Route `/my-renewals` kemungkinan gagal jika dipanggil.

### Import model tidak ada

Sumber:

- `PricingController` memiliki `use App\Models\Users;`.
- Model `App\Models\Users` tidak ditemukan.

Dampak:

- Import tidak terpakai atau akan membingungkan saat refactor.

## Risiko Error Handling Dan Idempotency

Temuan:

- Tidak ditemukan idempotency key.
- Beberapa operasi aktivasi dapat diklik/submit berulang.
- `updateOrInsert` dipakai pada sebagian flow, tetapi tidak merata.
- Email dikirim sinkron setelah beberapa update database.

Dampak:

- Duplicate request bisa membuat data ganda atau email ganda.
- Kegagalan email dapat memengaruhi UX setelah data berubah.

## Area Yang Butuh Test Tambahan

Prioritas tinggi:

- `HomeController@store`.
- `PricingController@uploadBukti`.
- `PricingController@updateStatus`.
- `PricingController@previewPrice`.
- `PricingController@proceedPayment`.
- `PricingController@uploadProof`.
- `PricingController@activateRenewal`.
- `MembershipController@store/update/destroy`.
- `OutletController@store/update/destroy`.
- `SendRenewalReminders`.
- `UpdatePricingPackage`.

Test saat ini hanya example test, sehingga flow bisnis utama belum terlindungi.

