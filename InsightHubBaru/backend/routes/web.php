<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriKontenController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\Auth\WebController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ─────────────────────────────────────────
// Authentication Routes (TIDAK butuh login)
// ─────────────────────────────────────────
Route::get('/login', [WebController::class, 'showLogin'])->name('login');
Route::post('/login', [WebController::class, 'login'])->name('login.post');

Route::get('/register', [WebController::class, 'showRegister'])->name('register');
Route::post('/register', [WebController::class, 'register'])->name('register.post');

// ─────────────────────────────────────────
// Semua route di bawah ini WAJIB login dulu
// ─────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [WebController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/metrics', [DashboardController::class, 'storeMetric'])->name('dashboard.metrics.store');
    Route::get('/dashboard/export/csv', [DashboardController::class, 'exportCsv'])->name('dashboard.export.csv');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

    // Master Data
    Route::resource('/kategori', KategoriKontenController::class)->except(['create', 'show', 'edit']);

    // AJAX Endpoint untuk Modal CRUD (Sesi Web)
    Route::apiResource('/ajax/kategori', \App\Http\Controllers\Api\KategoriController::class);

    // Master Data - Platform (read-only: 4 platform sudah di-seed lewat SQL,
    // halaman ini cuma utk lihat/toggle is_aktif, bukan CRUD penuh)
    Route::get('/platform', [PlatformController::class, 'index'])->name('master.platform.index');
    Route::patch('/platform/{platform}', [PlatformController::class, 'update'])->name('master.platform.update');

});

// Catatan: pages.Report.laporan.blade.php diasumsikan @include di dalam
// pages.Dashboard.index.blade.php (tab "Laporan"/"Pusat Rekap Data"),
// karena datanya (metricsLaporan, laporanAgg, lapFilters) sudah disiapkan
// sekaligus di DashboardController::index() -> tidak butuh route terpisah.
// pages.Report.insight.blade.php tidak dipakai -> boleh dihapus filenya.