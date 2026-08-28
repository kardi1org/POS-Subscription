# Development Guide

Panduan ini menjelaskan setup lokal, command penting, testing, build, dan aturan perubahan.

## Prasyarat

Berdasarkan `composer.json` dan `package.json`:

- PHP `^8.1`.
- Composer atau `composer.phar`.
- Node.js/npm.
- MySQL untuk default DB.
- Koneksi MySQL tambahan `db_pos` jika menjalankan flow integrasi POS.

## Setup Lokal

Langkah umum Laravel:

1. Install dependency PHP:
   - `composer install`, atau
   - `php composer.phar install` jika hanya `composer.phar` tersedia.
2. Install dependency frontend:
   - `npm install`.
3. Buat environment lokal dari contoh:
   - `copy .env.example .env` di Windows, atau
   - `cp .env.example .env` di Linux/macOS.
4. Generate app key:
   - `php artisan key:generate`.
5. Konfigurasi database default dan koneksi `db_pos` di `.env`.

Catatan keamanan:

- Jangan commit `.env`.
- Jangan menampilkan nilai rahasia dari `.env`.

## Menjalankan Aplikasi

- Laravel dev server:
  - `php artisan serve`
- Vite dev server:
  - `npm run dev`
- Build asset production:
  - `npm run build`

## Testing

Command:

- `php artisan test`
- `vendor/bin/phpunit`

Konfigurasi:

- `phpunit.xml` memakai `APP_ENV=testing`.
- `MAIL_MAILER=array`.
- `QUEUE_CONNECTION=sync`.
- SQLite testing dikomentari, sehingga test bisa memakai konfigurasi DB environment jika tidak diubah.

Test yang ada:

- `tests/Feature/ExampleTest.php`: hanya memastikan `/` status 200.
- `tests/Unit/ExampleTest.php`: hanya assertion true.

CI:

- `.github/workflows/tests.yml`
- Matrix PHP: 8.1 dan 8.2.
- Workflow menjalankan `composer update`, copy `.env.example`, `php artisan key:generate`, lalu `php artisan test`.

## Lint Dan Format

Package dev memuat `laravel/pint`.

Command yang lazim:

- `vendor/bin/pint`

Tidak ditemukan script Composer khusus untuk lint/format di `composer.json`.

## Migration Dan Database

Aturan wajib:

- Jangan menjalankan migration, seeder, reset, refresh, truncate, atau delete/update massal tanpa instruksi eksplisit.
- Jangan mengubah schema tanpa migration.
- Jangan mengedit dump SQL sebagai pengganti migration aplikasi.
- Saat menambah field yang dipakai controller/model/view, update migration, model fillable/cast, validasi, view, dan dokumentasi.
- Periksa sinkronisasi dengan `pos_subs.sql` dan schema POS jika flow menggunakan `db_pos`.

## Aturan Perubahan Kode

Sebelum mengubah flow subscription/renewal/payment:

- Telusuri route di `routes/web.php`.
- Telusuri controller terkait:
  - `HomeController`
  - `PricingController`
  - `MembershipController`
  - `OutletController`
- Telusuri model terkait:
  - `Pricing`
  - `Renewal`
  - `Package`
  - `User`
  - `MembershipUser`
- Telusuri Blade terkait:
  - `resources/views/home.blade.php`
  - `resources/views/admin/pricing/index.blade.php`
  - `resources/views/pricing/payment.blade.php`
  - email/invoice view di `resources/views/emails` dan `resources/views/invoices`.
- Telusuri command scheduler jika menyentuh status atau tanggal:
  - `SendRenewalReminders`
  - `UpdatePricingPackage`

Wajib diperiksa untuk perubahan transaksi:

- Authorization.
- Validation.
- Transaction boundary.
- Error handling.
- Duplicate request/idempotency.
- File upload cleanup.
- Dampak ke database default dan `db_pos`.
- Test feature untuk happy path dan failure path.

## Area Yang Perlu Hati-Hati

- Status `Aktif`/`aktif`, `Waiting Approval`/`waiting approval` tidak konsisten.
- `renewals.duration` dipakai tidak konsisten sebagai bulan dan hari.
- Flow yang menulis `db_pos` tidak sepenuhnya atomic bersama database default.
- Query langsung di Blade dapat menghasilkan N+1 dan membuat perubahan controller tidak cukup.
- `outlets` dan `outlet_user` dipakai kode tetapi belum terverifikasi dalam dump POS.
- Beberapa migration `down()` kosong atau tidak membalik perubahan.

## Menambah Test

Area prioritas test:

- Signup paket dan invoice.
- Upload bukti awal.
- Admin activation dan sync `db_pos`.
- Preview renewal/upgrade.
- Proceed payment renewal.
- Upload bukti renewal.
- Activate renewal.
- Membership store/update/destroy.
- Outlet store/update/destroy.
- Scheduler reminder.
- Scheduler update package.

Untuk test yang menyentuh email, gunakan `Mail::fake()`.
Untuk test yang menyentuh storage, gunakan `Storage::fake('public')`.
Untuk flow `db_pos`, siapkan strategi test connection/fake DB sebelum mengubah production code.

