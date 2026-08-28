# Code Architecture

Dokumen ini memetakan struktur kode dan alur route ke controller, model, view, command, event, dan integrasi.

## Pola Umum

Pola yang ditemukan adalah Laravel MVC sederhana:

- Route didefinisikan di `routes/web.php` dan `routes/api.php`.
- Controller memegang validasi, perhitungan, query, transaksi, upload file, email, dan sinkronisasi database eksternal.
- Model Eloquent mewakili tabel utama.
- View Blade juga mengandung sebagian query dan logika bisnis.
- Tidak ditemukan service layer, repository layer, action class, Livewire component, observer, listener custom, atau job queue custom.

## Route Map

### Public/Auth

- `Auth::routes()` dan `Auth::routes(['verify' => true])`
  - Sumber: `routes/web.php`.
  - Controller auth berada di `app/Http/Controllers/Auth`.
  - `RegisterController::create` membuat `users`.
  - `LoginController::authenticated` redirect admin ke `admin.pricing.index`, user ke `/home`.

- `GET /`
  - Controller: `LandingController@index`.
  - Model: `Package`.
  - View: `resources/views/landing.blade.php`.

- `GET /welkam`
  - Closure route menampilkan `resources/views/welcome.blade.php`.

### User Dashboard

- `GET /home`
  - Controller: `HomeController@index`.
  - Middleware: `auth`, `verified` dari constructor.
  - Model: `Pricing`, `Package`.
  - View: `resources/views/home.blade.php`.

- `GET /pricing/create/{id}`
  - Controller: `HomeController@create`.
  - View: `resources/views/pricing/create.blade.php`.

- `POST /pricing/store`
  - Controller: `HomeController@store`.
  - Model/tabel: `packages`, `pricings`.
  - Mail: `InvoiceSignupMail`.
  - Redirect: `pricing.transfer`.

- `GET /transfer/{id}`
  - Controller: `PricingController@transfer`.
  - Model: `Pricing`.
  - View: `resources/views/pricing/transfer.blade.php`.

- `POST /pricings/{pricing}/upload-bukti`
  - Controller: `PricingController@uploadBukti`.
  - Model/tabel: `pricings`, optional `renewals`.
  - Mail: `BuktiTransferUploadedMail`.

- `POST /pricing/{id}/preview`
  - Controller: `PricingController@previewPrice`.
  - Response: JSON.
  - Dipanggil dari inline JS di `resources/views/home.blade.php`.

- `POST /pricing/{id}/proceed-payment`
  - Controller: `PricingController@proceedPayment`.
  - Model/tabel: `pricings`, `packages`, `renewals`.
  - Mail: `InvoicePaymentMail`.
  - Redirect: `pricing.paymentPage`.

- `GET /pricing/payment/{renewal}`
  - Controller: `PricingController@paymentPage`.
  - Model: `Renewal` with `package`, `pricing`.
  - View: `resources/views/pricing/payment.blade.php`.

- `POST /pricing/payment/{renewal}/upload`
  - Controller: `PricingController@uploadProof`.
  - Model/tabel: `renewals`, `pricings`.
  - Mail: `BuktiTransferUploadedMail`.

### Membership And Outlet

- `POST /membership/store`
  - Controller: `MembershipController@store`.
  - Model/tabel lokal: `MembershipUser`, `Pricing`, `User`.
  - Integrasi `db_pos`: `users`, `model_has_roles`, `outlet_user`.

- `POST /membership/update/{id}`
  - Controller: `MembershipController@update`.
  - Model/tabel lokal: `membership_users`.
  - Integrasi `db_pos`: `users`, `model_has_roles`, `outlet_user`.

- `DELETE /membership/delete/{id}`
  - Controller: `MembershipController@destroy`.
  - Model/tabel lokal: `membership_users`.
  - Integrasi `db_pos`: `model_has_roles`, `users`.

- `POST /outlet/store`
  - Controller: `OutletController@store`.
  - Integrasi `db_pos`: `outlets`.

- `PUT /outlet/{id}`
  - Controller: `OutletController@update`.
  - Integrasi `db_pos`: `outlets`.

- `DELETE /outlet/{id}`
  - Controller: `OutletController@destroy`.
  - Integrasi `db_pos`: `outlet_user`, `outlets`.

### Admin

- Middleware admin:
  - Class: `App\Http\Middleware\AdminMiddleware`.
  - Rule: user harus login dan `Auth::user()->role === 'admin'`.

- `GET /admin/pricings`
  - Controller: `PricingController@index`.
  - Model/tabel: `pricings`.
  - Raw SQL: `SHOW DATABASES`.
  - View: `resources/views/admin/pricing/index.blade.php`.

- `PUT /admin/admin/pricing/{id}/update-status`
  - Route berada di group prefix `admin`, tetapi path string juga mengandung `/admin/...`.
  - Controller: `PricingController@updateStatus`.
  - Model/tabel lokal: `pricings`, `users`.
  - Integrasi `db_pos`: `users`, `outlets`, `outlet_user`, `model_has_roles`.
  - Mail: `StatusAktifMail`.

- `POST /admin/pricing/activate-renewal/{id}`
  - Controller: `PricingController@activateRenewal`.
  - Model/tabel lokal: `renewals`, `pricings`, `packages`, `membership_users`.
  - Integrasi `db_pos`: `users`.
  - Mail: `RenewalNotification`.

### API

- `GET /api/user`
  - Middleware: `auth:sanctum`.
  - Response: authenticated user.

## Model Map

- `App\Models\User`
  - Tabel: `users`.
  - Fillable: `name`, `email`, `password`, `db_host`, `db_port`, `db_database`, `db_username`, `db_password`.
  - Implements `MustVerifyEmail`.
  - Custom email verification/reset mail via `sendEmailVerificationNotification` and `sendPasswordResetNotification`.

- `App\Models\Pricing`
  - Tabel: `pricings`.
  - Fillable: `codepaket`, `namapaket`, `harga_paket`, `durasi`, `email`, `status`, `bukti_transfer`, `reminder_sent_at`, `start_date`, `end_date`.
  - Casts: date/time fields.
  - Relationships: `renewals()`, `package()`, `user()`.

- `App\Models\Renewal`
  - Tabel: `renewals`.
  - Fillable: `pricing_id`, `old_package`, `new_package`, `duration`, `total_price`, `status`, `bukti_transfer`, `old_end_date`, `new_end_date`, `approved_by`.
  - Relationships: `package()`, `pricing()`, `user()`, `oldPackage()`.

- `App\Models\Package`
  - Tabel default: `packages`.
  - Tidak ada fillable/cast custom.

- `App\Models\MembershipUser`
  - Tabel default: `membership_users`.
  - Fillable: `pricing_id`, `name`, `email`, `userpassword`, `level`, credential DB.
  - Relationship: `pricing()`.

## View And Frontend

- `resources/views/landing.blade.php`
  - Menampilkan paket dari `Package`.
  - Memakai Bootstrap CDN dan inline JS.

- `resources/views/home.blade.php`
  - Dashboard user.
  - Menampilkan pricing, upload bukti, renewal modal, membership management, outlet management.
  - Mengandung query langsung ke `Renewal`, `MembershipUser`, `Package`, dan `DB::connection('db_pos')`.
  - Inline JS memanggil `/pricing/{id}/preview`.

- `resources/views/admin/pricing/index.blade.php`
  - Dashboard admin pricing.
  - Mengandung query langsung ke `Renewal` dan `Package`.
  - Form update status dan aktivasi renewal.

- `resources/js/app.js`
  - Vue app scaffolding dengan `ExampleComponent`.

- `resources/js/bootstrap.js`
  - Bootstrap dan Axios.

## Commands And Scheduler

- `App\Console\Commands\SendRenewalReminders`
  - Signature: `pricing:send-reminders`.
  - Scheduler: daily `08:00`.
  - Membaca `pricings` status `Aktif`, `end_date`, `reminder_sent_at`.
  - Mengirim `RenewalReminderMail`.

- `App\Console\Commands\UpdatePricingPackage`
  - Signature: `pricing:update-package`.
  - Scheduler: daily `00:10`.
  - Membaca `renewals` status `aktif`, `old_end_date`, `new_end_date`.
  - Mengubah `pricings.codepaket` dan `pricings.namapaket`.

## Events, Jobs, Queues

- Event terverifikasi: `Registered` ke `SendEmailVerificationNotification` di `App\Providers\EventServiceProvider`.
- Tidak ditemukan job custom.
- Tidak ditemukan observer.
- Tidak ditemukan listener custom selain default Laravel.
- Tidak ditemukan webhook/API integration external selain akses database `db_pos` dan email.

## Integrasi Database POS

Koneksi `db_pos` didefinisikan di `config/database.php`.

Kode yang memakai koneksi ini:

- `PricingController@index`: `SHOW DATABASES`.
- `PricingController@updateStatus`: `users`, `outlets`, `outlet_user`, `model_has_roles`.
- `PricingController@activateRenewal`: `users`.
- `MembershipController@store/update/destroy`: `users`, `model_has_roles`, `outlet_user`.
- `OutletController@store/update/destroy`: `outlets`, `outlet_user`.
- `resources/views/home.blade.php`: query `outlets`, `outlet_user`.

Belum pasti: schema dump `pos_subs_new.sql` tidak memuat `outlets` dan `outlet_user`, meskipun kode memakainya.

