# Database Schema

Dokumen ini mendokumentasikan schema yang ditemukan dari migration, model, SQL dump, dan penggunaan kode.

## Sumber

- Migration: `database/migrations`.
- Seeder: `database/seeders/PackageSeeder.php`.
- Model: `app/Models`.
- Dump subscription: `pos_subs.sql`.
- Dump POS: `pos_subs_new.sql`.
- Penggunaan query: `app/Http/Controllers`, `resources/views`.

Nilai data dari `INSERT` tidak didokumentasikan untuk menghindari penyebaran data akun/credential.

## Database Subscription

### `users`

Sumber:

- `database/migrations/2014_10_12_000000_create_users_table.php`
- `database/migrations/2025_09_26_041158_add_is_admin_to_users_table.php`
- `database/migrations/2025_09_26_064147_add_role_to_users_table.php`
- `database/migrations/2025_10_29_024606_add_host_db_to_users_table.php`
- `database/migrations/2025_12_22_104236_change_password_columns_to_text.php`
- `app/Models/User.php`
- `pos_subs.sql`

Field penting:

- `id`: bigint unsigned, primary key.
- `name`: string, required.
- `email`: string, required, unique.
- `is_admin`: boolean, default `0`.
- `email_verified_at`: timestamp nullable.
- `password`: string, required.
- `remember_token`: nullable.
- `db_host`: string nullable.
- `db_port`: string nullable.
- `db_database`: string nullable.
- `db_username`: string nullable.
- `db_password`: text nullable pada dump/migration change.
- `role`: string default `user`.
- `created_at`, `updated_at`: timestamps nullable.

Index:

- Primary key: `id`.
- Unique: `email`.

Model:

- Class: `App\Models\User`.
- Fillable: `name`, `email`, `password`, `db_host`, `db_port`, `db_database`, `db_username`, `db_password`.
- Hidden: `password`, `remember_token`.
- Cast: `email_verified_at` as datetime.

Catatan:

- Auth admin memakai `role === 'admin'`, bukan `is_admin`.

### `packages`

Sumber:

- `database/migrations/2025_10_14_033046_create_packages_table.php`
- `database/migrations/2026_01_09_093240_update_price_on_packages_table.php`
- `database/seeders/PackageSeeder.php`
- `app/Models/Package.php`
- `pos_subs.sql`

Field:

- `id`: bigint unsigned, primary key.
- `name`: string, required.
- `price`: decimal. Migration awal `decimal(8,2)`, migration update mengubah menjadi `decimal(15,2)`.
- `created_at`, `updated_at`.

Seeder:

- `Basic`, `Pro`, `Premium` dengan harga masing-masing di `PackageSeeder`.

Model:

- Class: `App\Models\Package`.
- Tidak ada `$fillable` custom.

### `pricings`

Sumber:

- `database/migrations/2025_09_25_034102_create_pricings_table.php`
- `database/migrations/2025_09_26_015924_add_bukti_transfer_to_pricings_table.php`
- `database/migrations/2025_10_06_073050_add_masa_aktif_to_pricings_table.php`
- `database/migrations/2025_10_10_100839_add_reminder_sent_at_to_pricings_table.php`
- `database/migrations/2025_10_21_043949_add_harga_paket_and_durasi_to_pricings_table.php`
- `app/Models/Pricing.php`
- `pos_subs.sql`

Field:

- `id`: bigint unsigned, primary key.
- `email`: string, required. Dipakai sebagai penghubung ke `users.email`.
- `codepaket`: string, required. Kode menyimpan id `packages.id`.
- `namapaket`: string, required.
- `harga_paket`: decimal(15,2), nullable.
- `durasi`: integer, nullable.
- `notes`: text nullable.
- `status`: enum dari migration: `Pending`, `Active`, `Nonaktif`, `Waiting Approval`, `Aktif`.
- `bukti_transfer`: string nullable.
- `start_date`: date nullable.
- `end_date`: date nullable.
- `reminder_sent_at`: timestamp nullable.
- `created_at`, `updated_at`.

Index:

- Primary key: `id`.
- Tidak ditemukan foreign key dari `email` atau `codepaket`.

Model:

- Class: `App\Models\Pricing`.
- Fillable: `codepaket`, `namapaket`, `harga_paket`, `durasi`, `email`, `status`, `bukti_transfer`, `reminder_sent_at`, `start_date`, `end_date`.
- Casts: `start_date`, `end_date`, `reminder_sent_at` as datetime.
- Relationships:
  - `renewals()`: hasMany `Renewal`.
  - `package()`: belongsTo `Package` via `codepaket`.
  - `user()`: belongsTo `User` via local `email` to owner `email`.

Status yang dipakai kode:

- `Pending`
- `Waiting Approval`
- `waiting approval`
- `Aktif`
- `aktif`
- `nonaktif`

Catatan risiko:

- Status lowercase yang dipakai controller tidak sama dengan enum migration.

### `renewals`

Sumber:

- `database/migrations/2025_10_10_065826_create_renewals_table.php`
- `database/migrations/2025_10_14_035544_add_package_columns_to_renewals_table.php`
- `database/migrations/2025_10_15_030351_add_payment_fields_to_renewals_table.php`
- `app/Models/Renewal.php`
- `pos_subs.sql`

Field:

- `id`: bigint unsigned, primary key.
- `pricing_id`: foreign id ke `pricings.id`, cascade delete.
- `duration`: integer, required. Di beberapa flow berisi bulan, di flow lain berisi hari.
- `total_price`: decimal(15,2) nullable.
- `status`: string default `waiting approval`.
- `bukti_transfer`: string nullable.
- `old_end_date`: date nullable.
- `new_end_date`: date required.
- `approved_by`: string nullable.
- `old_package`: unsigned bigint nullable, berisi id package lama.
- `new_package`: unsigned bigint nullable, berisi id package baru.
- `created_at`, `updated_at`.

Index/foreign key:

- Primary key: `id`.
- Index/FK: `pricing_id` references `pricings.id` on delete cascade.
- Tidak ditemukan FK untuk `old_package` atau `new_package`.

Model:

- Class: `App\Models\Renewal`.
- Fillable: `pricing_id`, `old_package`, `new_package`, `duration`, `total_price`, `status`, `bukti_transfer`, `old_end_date`, `new_end_date`, `approved_by`.
- Dates: `old_end_date`, `new_end_date`.
- Relationships:
  - `package()`: belongsTo `Package` via `new_package`.
  - `pricing()`: belongsTo `Pricing`.
  - `oldPackage()`: belongsTo `Package` via `old_package`.
  - `user()`: hasOneThrough `User` melalui `Pricing`, tetapi menggunakan `Pricing.user_id` yang tidak ditemukan di schema.

Status yang dipakai kode:

- `waiting approval`
- `Waiting Approval`
- `Aktif`
- `aktif`

### `membership_users`

Sumber:

- `database/migrations/2025_11_03_064903_create_membership_users_table.php`
- `database/migrations/2025_11_03_085318_add_db_credentials_to_membership_users_table.php`
- `app/Models/MembershipUser.php`
- `pos_subs.sql`

Field:

- `id`: bigint unsigned, primary key.
- `pricing_id`: unsigned bigint, FK ke `pricings.id`, cascade delete.
- `name`: string, required.
- `email`: string, required, unique.
- `userpassword`: string, required.
- `level`: enum migration `admin`, `kasir`.
- `db_database`: string nullable.
- `db_host`: string nullable.
- `db_port`: string nullable.
- `db_username`: string nullable.
- `db_password`: string nullable pada dump.
- `created_at`, `updated_at`.

Index/foreign key:

- Primary key: `id`.
- Unique: `email`.
- FK: `pricing_id` references `pricings.id` on delete cascade.

Model:

- Class: `App\Models\MembershipUser`.
- Fillable: `pricing_id`, `name`, `email`, `userpassword`, `level`, DB credential fields.
- Relationship: `pricing()`.

Catatan risiko:

- `MembershipController` validasi `level` memakai `manager,kasir`, tidak sama dengan enum migration `admin,kasir`.

### Tabel Laravel Lain

- `failed_jobs`: Laravel failed jobs table. PK `id`, unique `uuid`.
- `personal_access_tokens`: Sanctum token table. PK `id`, unique `token`, index `tokenable_type, tokenable_id`.
- `password_reset_tokens`: PK `email`.
- `password_resets`: index `email`.
- `migrations`: PK `id`.

## Database POS Dari `pos_subs_new.sql`

`pos_subs_new.sql` berisi banyak tabel POS. Kode subscription hanya memakai sebagian tabel melalui koneksi `db_pos`.

Tabel POS yang ditemukan dan relevan dengan kode:

### `users`

Sumber: `pos_subs_new.sql`, penggunaan di `PricingController` dan `MembershipController`.

Field yang ditemukan di dump:

- `id`: primary key.
- `name`
- `email`: unique.
- `email_verified_at`
- `password`
- `is_active`
- `remember_token`
- `tenant_database`
- `tenant_host`
- `tenant_port`
- `tenant_username`
- `tenant_password`
- `valid_date`
- `created_at`, `updated_at`

Field yang dipakai kode tetapi belum ditemukan di `CREATE TABLE users` dump POS:

- `codepaket`
- `level`

### `roles`

Sumber: `pos_subs_new.sql`.

Field:

- `id`: primary key.
- `name`
- `guard_name`
- `created_at`, `updated_at`

Index:

- Unique: `name`, `guard_name`.

### `model_has_roles`

Sumber: `pos_subs_new.sql`, penggunaan di controller.

Field:

- `role_id`
- `model_type`
- `model_id`

Index/FK:

- Primary key: `role_id`, `model_id`, `model_type`.
- FK: `role_id` references `roles.id` cascade delete.

### `outlets` dan `outlet_user`

Sumber penggunaan kode:

- `PricingController@updateStatus`
- `MembershipController@store`
- `MembershipController@update`
- `OutletController@store/update/destroy`
- `resources/views/home.blade.php`

Belum terverifikasi di dump:

- `CREATE TABLE outlets` tidak ditemukan di `pos_subs_new.sql`.
- `CREATE TABLE outlet_user` tidak ditemukan di `pos_subs_new.sql`.

Field yang diasumsikan oleh kode:

- `outlets`: `id`, `email`, `name`, `address`, `telp`, `info`, `created_at`, `updated_at`.
- `outlet_user`: `user_id`, `outlet_id`, `is_primary`, `created_at`, `updated_at`.

## Stored Procedure, Trigger, View

Tidak ditemukan definisi stored procedure, trigger, atau database view dari pencarian repository terhadap `database`, `pos_subs.sql`, dan `pos_subs_new.sql`.

## Inkonsistensi Yang Harus Diverifikasi

- `renewals.duration` dipakai sebagai hari pada `PricingController@renew`, tetapi sebagai bulan pada `PricingController@proceedPayment` dan ditampilkan dikali 30 pada beberapa view.
- `pricings.status` enum migration tidak selaras dengan lowercase status yang dipakai controller/view.
- `membership_users.level` migration tidak selaras dengan validasi controller.
- `Renewal::user()` merujuk field `pricings.user_id` yang tidak ditemukan.
- Tabel/field POS tertentu dipakai kode tetapi tidak ditemukan dalam dump POS yang diperiksa.

