# AGENTS.md

Panduan operasional untuk Codex/developer saat bekerja di repository ini.

## Project

- Aplikasi Laravel untuk registrasi subscription POS.
- Framework backend: Laravel 10, PHP `^8.1`.
- Frontend utama: Blade + Bootstrap. Vite/Vue tersedia sebagai scaffolding, tetapi UI bisnis utama berada di Blade.
- Database utama: MySQL default Laravel.
- Integrasi database lain: koneksi `db_pos` di `config/database.php`, dipakai untuk sinkronisasi user/outlet POS.

Dokumentasi detail:

- `docs/PROJECT_OVERVIEW.md`
- `docs/CODE_ARCHITECTURE.md`
- `docs/DATABASE_SCHEMA.md`
- `docs/BUSINESS_FLOWS.md`
- `docs/DEVELOPMENT_GUIDE.md`
- `docs/KNOWN_RISKS.md`

## Struktur Penting

- `routes/web.php`: route utama web, auth, user dashboard, admin pricing.
- `routes/api.php`: hanya endpoint `/api/user` dengan Sanctum.
- `app/Http/Controllers`: controller bisnis subscription, pricing, membership, outlet, auth.
- `app/Models`: model Eloquent utama (`User`, `Pricing`, `Renewal`, `Package`, `MembershipUser`).
- `app/Mail`: email invoice, verification, reminder, bukti transfer, status aktif.
- `app/Console/Commands`: command scheduler renewal/reminder.
- `resources/views`: Blade UI, invoice, email template.
- `database/migrations`: schema database subscription.
- `pos_subs.sql`: dump schema/data subscription.
- `pos_subs_new.sql`: dump schema POS yang tampaknya terpisah dari migration subscription.

## Command Umum

- Install PHP dependency: `php composer.phar install` atau `composer install` jika Composer tersedia di PATH.
- Install frontend dependency: `npm install`.
- Jalankan app lokal: `php artisan serve`.
- Jalankan Vite dev server: `npm run dev`.
- Build frontend: `npm run build`.
- Test: `php artisan test`.
- PHPUnit langsung: `vendor/bin/phpunit`.
- CI memakai GitHub Actions `php artisan test` pada PHP 8.1 dan 8.2 di `.github/workflows/tests.yml`.

## Pola Arsitektur Yang Ada

- Pertahankan pola MVC sederhana yang sudah ada.
- Belum ada service/repository/action layer; jangan menambah layer baru tanpa kebutuhan jelas.
- Banyak logika bisnis saat ini berada di controller dan sebagian query berada di Blade. Jika refactor, telusuri semua route/view pemanggil sebelum mengubah behavior.
- Model memakai `$fillable`, relationship Eloquent, dan query builder langsung.
- Email dikirim sinkron via `Mail::to(...)->send(...)`.
- Proses background yang terverifikasi adalah Artisan command terjadwal, bukan job queue custom.

## Aturan Keamanan Dan Data

- Jangan membaca atau menampilkan nilai rahasia dari `.env`.
- Jangan menjalankan command database destruktif tanpa instruksi eksplisit user.
- Dilarang menjalankan `migrate:fresh`, `migrate:refresh`, `db:wipe`, seeder destruktif, truncate, delete massal, update massal, reset, atau command serupa tanpa persetujuan eksplisit.
- Jangan mengubah schema database tanpa migration.
- Jangan mengubah business flow tanpa menelusuri seluruh pemanggil route, controller, view, model, mail, command, dan integrasi `db_pos`.
- Saat mengubah transaksi bisnis, wajib memeriksa authorization, validation, transaction boundary, error handling, idempotency/duplicate request, dan test.
- Saat menyentuh file upload bukti transfer, cek validasi file, storage disk, path publik, dan penghapusan file lama.
- Saat menyentuh sinkronisasi `db_pos`, cek risiko konsistensi lintas database.

## Area Sensitif

- Status subscription/renewal memakai variasi huruf besar/kecil yang tidak konsisten.
- `MembershipController` dan migration `membership_users.level` tidak sepenuhnya sinkron.
- Kode memakai tabel POS `outlets` dan `outlet_user`, tetapi tabel tersebut belum terverifikasi di `pos_subs_new.sql`.
- Beberapa transaksi menulis ke database default dan `db_pos` dalam flow yang sama.
- Blade `resources/views/home.blade.php` dan `resources/views/admin/pricing/index.blade.php` berisi query dan logika bisnis.

