<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\RenewalLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welkam', function () {
    return view('welcome');
});

Auth::routes();

//verifikasi email user
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    return view('landing');
});

Route::get('/pricing/create/{id}', [App\Http\Controllers\HomeController::class, 'create'])->name('pricing.create');

Route::post('/pricing/store', [App\Http\Controllers\HomeController::class, 'store'])->name('pricing.store');
Route::get('/transfer/{id}', [App\Http\Controllers\PricingController::class, 'transfer'])->name('pricing.transfer');
Route::post('/pricings/{pricing}/upload-bukti', [App\Http\Controllers\PricingController::class, 'uploadBukti'])->name('pricings.uploadBukti');
Route::post('/pricing/{id}/renew', [App\Http\Controllers\PricingController::class, 'renew'])->name('pricing.renew');

// Route::get('/pricing/{id}/payment', [App\Http\Controllers\PricingController::class, 'showPayment'])->name('pricing.payment');
// Route::post('/pricing/{id}/upload-payment', [App\Http\Controllers\PricingController::class, 'uploadPayment'])->name('pricing.uploadPayment');

// Route::post('/pricing/{id}/proceed-payment', [App\Http\Controllers\PricingController::class, 'proceedPayment'])->name('pricing.proceedPayment');
// Route::get('/pricing/{id}/payment', [App\Http\Controllers\PricingController::class, 'showPayment'])->name('pricing.payment');
// Route::post('/pricing/{id}/upload-payment', [App\Http\Controllers\PricingController::class, 'uploadPayment'])->name('pricing.uploadPayment');

Route::post('/pricing/{id}/proceed-payment', [App\Http\Controllers\PricingController::class, 'proceedPayment'])->name('pricing.proceedPayment');
Route::get('/pricing/payment/{renewal}', [App\Http\Controllers\PricingController::class, 'paymentPage'])->name('pricing.paymentPage');
Route::post('/pricing/payment/{renewal}/upload', [App\Http\Controllers\PricingController::class, 'uploadProof'])->name('pricing.uploadProof');
Route::post('/membership/store', [MembershipController::class, 'store'])->name('membership.store');
Route::post('/membership/update/{id}', [MembershipController::class, 'update'])->name('membership.update');
Route::delete('/membership/delete/{id}', [MembershipController::class, 'destroy'])->name('membership.destroy');
Route::post('/pricing/{id}/preview', [PricingController::class, 'previewPrice'])->name('pricing.preview');

Route::middleware(['auth'])->group(function () {
    Route::get('/my-renewals', [RenewalLogController::class, 'index'])->name('user.renewals');
});




Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/pricings', [App\Http\Controllers\PricingController::class, 'index'])
        ->name('admin.pricing.index');
    //Route::put('/admin/pricing/{id}/status', [App\Http\Controllers\PricingController::class, 'updateStatus'])->name('admin.pricing.updateStatus');
    Route::put('/admin/pricing/{id}/update-status', [App\Http\Controllers\PricingController::class, 'updateStatus'])
        ->name('admin.pricing.updateStatus');
    Route::post('/pricing/activate-renewal/{id}', [PricingController::class, 'activateRenewal'])
        ->name('pricing.activateRenewal');
});
