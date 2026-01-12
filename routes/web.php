<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentPromoController;
use App\Http\Controllers\PackageTypeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\StreamingAddonController;
use App\Http\Controllers\TvAddonController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman login (guest only)
require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

// ===============================================
// SEMUA ROUTE YANG HANYA BISA DIAKSES SETELAH LOGIN
// ===============================================
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    // ===========================================
    // 5. MANAJEMEN USER — TETAP ADA & AMAN 100%
    // ===========================================
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'data'])->name('data');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // 6. MANAJEMEN ROLE — TETAP ADA & AMAN 100%
    // ===========================================
    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/data', [RoleController::class, 'data'])->name('data');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN BANNER & PROMO
    // ===========================================
    Route::prefix('admin/banners')->name('admin.banners.')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('index');
        Route::get('/data', [BannerController::class, 'data'])->name('data');
        Route::post('/', [BannerController::class, 'store'])->name('store');
        Route::get('/{banner}', [BannerController::class, 'show'])->name('show');
        Route::put('/{banner}', [BannerController::class, 'update'])->name('update');
        Route::delete('/{banner}', [BannerController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN KEUNGGULAN MYREPUBLIC
    // ===========================================
    Route::prefix('admin/features')->name('admin.features.')->group(function () {
        Route::get('/', [FeatureController::class, 'index'])->name('index');
        Route::get('/data', [FeatureController::class, 'data'])->name('data');
        Route::post('/', [FeatureController::class, 'store'])->name('store');
        Route::get('/{feature}', [FeatureController::class, 'show'])->name('show');
        Route::put('/{feature}', [FeatureController::class, 'update'])->name('update');
        Route::delete('/{feature}', [FeatureController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN KATEGORI UTAMA
    // ===========================================
    Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/data', [CategoryController::class, 'data'])->name('data');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN PROMO PEMBAYARAN
    // ===========================================
    Route::prefix('admin/payment-promos')->name('admin.payment-promos.')->group(function () {
        Route::get('/', [PaymentPromoController::class, 'index'])->name('index');
        Route::get('/data', [PaymentPromoController::class, 'data'])->name('data');
        Route::post('/', [PaymentPromoController::class, 'store'])->name('store');
        Route::get('/{paymentPromo}', [PaymentPromoController::class, 'show'])->name('show');
        Route::put('/{paymentPromo}', [PaymentPromoController::class, 'update'])->name('update');
        Route::delete('/{paymentPromo}', [PaymentPromoController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN TIPE PAKET
    // ===========================================
    Route::prefix('admin/package-types')->name('admin.package-types.')->group(function () {
        Route::get('/', [PackageTypeController::class, 'index'])->name('index');
        Route::get('/data', [PackageTypeController::class, 'data'])->name('data');
        Route::post('/', [PackageTypeController::class, 'store'])->name('store');
        Route::get('/{packageType}', [PackageTypeController::class, 'show'])->name('show');
        Route::put('/{packageType}', [PackageTypeController::class, 'update'])->name('update');
        Route::delete('/{packageType}', [PackageTypeController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN DATA PAKET INTERNET
    // ===========================================
    Route::prefix('admin/packages')->name('admin.packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/data', [PackageController::class, 'data'])->name('data');
        Route::post('/', [PackageController::class, 'store'])->name('store');
        Route::get('/{package}', [PackageController::class, 'show'])->name('show');
        Route::put('/{package}', [PackageController::class, 'update'])->name('update');
        Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
        Route::get('types/{categoryId}', [PackageController::class, 'getTypes']);
        Route::get('promos/category/{categoryId}', [PackageController::class, 'getPromosByCategory']);
    });

    Route::prefix('admin/benefits')->name('admin.benefits.')->group(function () {
        Route::get('/list', [BenefitController::class, 'listForDropdown'])->name('list');
        Route::get('/', [BenefitController::class, 'index'])->name('index');
        Route::get('/data', [BenefitController::class, 'data'])->name('data');
        Route::post('/', [BenefitController::class, 'store'])->name('store');
        Route::get('/{benefit}', [BenefitController::class, 'show'])->name('show');
        Route::put('/{benefit}', [BenefitController::class, 'update'])->name('update');
        Route::delete('/{benefit}', [BenefitController::class, 'destroy'])->name('destroy');
    });

    // Grup route untuk streaming add-ons (mirip benefits)
    Route::prefix('admin/streaming-addons')->name('admin.streaming-addons.')->group(function () {
        Route::get('/list', [StreamingAddonController::class, 'listForDropdown'])->name('list');
        Route::get('/', [StreamingAddonController::class, 'index'])->name('index');
        Route::get('/data', [StreamingAddonController::class, 'data'])->name('data');
        Route::post('/', [StreamingAddonController::class, 'store'])->name('store');
        Route::get('/{streamingAddon}', [StreamingAddonController::class, 'show'])->name('show');
        Route::put('/{streamingAddon}', [StreamingAddonController::class, 'update'])->name('update');
        Route::delete('/{streamingAddon}', [StreamingAddonController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN HIBURAN TERLENGKAP (TV ADD-ON)
        // ===========================================
    Route::prefix('admin/tv-addon')->name('admin.tv-addon.')->group(function () {
        Route::get('/', [TvAddonController::class, 'index'])->name('index');
        Route::post('/update', [TvAddonController::class, 'update'])->name('update');
    });

    Route::prefix('admin/channels')->name('admin.channels.')->group(function () {
        Route::get('/', [ChannelController::class, 'index'])->name('index');
        Route::get('/data', [ChannelController::class, 'data'])->name('data');
        Route::post('/', [ChannelController::class, 'store'])->name('store');
        Route::get('/{channel}', [ChannelController::class, 'show'])->name('show');
        Route::put('/{channel}', [ChannelController::class, 'update'])->name('update');
        Route::delete('/{channel}', [ChannelController::class, 'destroy'])->name('destroy');
    });

    // ===========================================
    // MANAJEMEN SYARAT DAN KETENTUAN
    // ===========================================
    Route::prefix('admin/terms')->name('admin.terms.')->group(function () {
        Route::get('/', [TermController::class, 'edit'])->name('index');
        Route::post('/', [TermController::class, 'update'])->name('update'); // Ubah ke POST
    });


    Route::get('/admin/leads', [LeadController::class, 'index'])->name('lead.index');
    Route::get('/admin/leads/data', [LeadController::class, 'data'])->name('lead.data');
    Route::delete('/admin/leads/{id}', [LeadController::class, 'destroy'])->name('lead.destroy');
});