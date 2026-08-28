# Project Overview

Dokumen ini merangkum fakta project yang ditemukan dari repository.

## Ringkasan

Repository ini berisi aplikasi Laravel untuk registrasi dan pengelolaan subscription POS. Nama singkat dari `README.md` adalah "Aplikasi Regitrasi Subscription POS".

Fungsi yang terverifikasi dari kode:

- Landing page menampilkan paket subscription.
- User mendaftar paket dan mengunggah bukti transfer.
- Admin melihat daftar pricing/subscription dan mengaktifkan status.
- User aktif dapat membuat proses renewal/upgrade paket.
- Admin dapat mengaktifkan renewal.
- User dapat mengelola membership user tambahan dan outlet yang disinkronkan ke database POS melalui koneksi `db_pos`.
- Scheduler mengirim reminder masa aktif dan memperbarui package pricing dari renewal tertentu.

## Teknologi

Sumber: `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `vite.config.js`.

- PHP: constraint `^8.1`.
- Laravel Framework: `v10.48.28` di `composer.lock`.
- Laravel Sanctum: `v3.3.3`.
- Laravel UI: `v4.6.1`.
- Laravel Breeze: `v1.23.0`.
- DomPDF: `barryvdh/laravel-dompdf v3.1.1`.
- PHPUnit: `10.5.45`.
- Frontend: Vite, Vue, Bootstrap, Axios, Sass.
- Vue terpasang dan `resources/js/app.js` mount ke `#app`, tetapi UI bisnis utama yang ditemukan memakai Blade dan inline JavaScript.

## Struktur Repository

- `app/Console/Commands`
  - `SendRenewalReminders`
  - `UpdatePricingPackage`
- `app/Http/Controllers`
  - `HomeController`
  - `LandingController`
  - `PricingController`
  - `MembershipController`
  - `OutletController`
  - Auth controllers dari Laravel UI.
- `app/Http/Middleware`
  - `AdminMiddleware` memakai `Auth::user()->role === 'admin'`.
- `app/Mail`
  - `InvoiceSignupMail`, `InvoicePaymentMail`, `RenewalReminderMail`, `RenewalNotification`, `StatusAktifMail`, `BuktiTransferUploadedMail`, `CustomVerifyEmail`, `CustomResetPassword`, `SendEmail`.
- `app/Models`
  - `User`, `Pricing`, `Renewal`, `Package`, `MembershipUser`.
- `database/migrations`
  - Migration subscription database.
- `resources/views`
  - Blade untuk landing, home dashboard, admin pricing, payment, email, invoice.
- `routes`
  - `web.php`, `api.php`, `console.php`, `channels.php`.
- `.github/workflows`
  - Workflow test dan workflow bawaan Laravel untuk issue/PR/changelog.

## Entry Point

- HTTP app: `public/index.php`.
- Bootstrap Laravel: `bootstrap/app.php`.
- Web route: `routes/web.php`.
- API route: `routes/api.php`.
- Console scheduler: `app/Console/Kernel.php`.

## Database Yang Ditemukan

Database subscription dari migration dan `pos_subs.sql`:

- `users`
- `pricings`
- `renewals`
- `packages`
- `membership_users`
- tabel Laravel: `failed_jobs`, `personal_access_tokens`, `password_resets`, `password_reset_tokens`, `migrations`

Database POS dari `pos_subs_new.sql` tampak sebagai schema aplikasi berbeda yang diakses melalui koneksi `db_pos`. Tabel POS yang ditemukan antara lain:

- `users`
- `roles`
- `model_has_roles`
- `permissions`
- `products`
- `sales`
- `sale_details`
- `payments`
- `purchases`
- `purchase_details`
- `shifts`
- `settings`
- `mejas`

Belum pasti: kode memakai `db_pos.outlets` dan `db_pos.outlet_user`, tetapi kedua `CREATE TABLE` tersebut belum ditemukan di `pos_subs_new.sql`.

## Batasan Verifikasi

- `.env` tidak dibaca.
- Database runtime tidak di-query.
- Migration, seeder, dan command yang dapat mengubah data tidak dijalankan.
- Kesimpulan dibuat dari file repository: kode PHP, Blade, migration, config, SQL dump, manifest, dan workflow.

