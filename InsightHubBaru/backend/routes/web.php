<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriKontenController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\Auth\WebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExportRequestController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ─────────────────────────────────────────
// Authentication Routes (TIDAK butuh login)
// ─────────────────────────────────────────
Route::get('/login', [WebController::class, 'showLogin'])->name('login');
Route::post('/login', [WebController::class, 'login'])->name('login.post');


// ─────────────────────────────────────────
// Semua route di bawah ini WAJIB login dulu
// ─────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [WebController::class, 'logout'])->name('logout');

    // Route untuk ganti password wajib (harus bisa diakses meski must_change_password = true)
    Route::get('/change-password', [\App\Http\Controllers\Auth\ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change.show');
    Route::post('/change-password', [\App\Http\Controllers\Auth\ChangePasswordController::class, 'changePassword'])->name('password.change.post');

    // Route lain yang TERLINDUNGI dari user yang belum ganti password
    Route::middleware('force.change.password')->group(function () {
        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])
            ->name('profile.show')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':profil.lihat,view');
            
        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':profil.edit,edit');
            
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':profil.ubah-password,edit');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':ringkasan.lihat,view');

        Route::post('/dashboard/metrics', [DashboardController::class, 'storeMetric'])->name('dashboard.metrics.store');
        
        Route::post('/dashboard/metrics/import', [DashboardController::class, 'importExcel'])
            ->name('dashboard.metrics.import')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.import,create');

        Route::put('/dashboard/metrics/{platform}/{id}', [DashboardController::class, 'updateMetric'])
            ->name('dashboard.metrics.update')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.edit,edit');

        Route::delete('/dashboard/metrics/{platform}/{id}', [DashboardController::class, 'destroyMetric'])
            ->name('dashboard.metrics.destroy')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.hapus,delete');

        Route::get('/dashboard/export/csv', [DashboardController::class, 'exportCsv'])
            ->name('dashboard.export.csv')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':laporan.export-csv,view');

        Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])
            ->name('dashboard.export.excel')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':laporan.export-excel,view');

        Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])
            ->name('dashboard.export.pdf')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':laporan.export-pdf,view');

        Route::get('/dashboard/export/ringkasan/pdf', [DashboardController::class, 'exportRingkasanPdf'])
            ->name('dashboard.export.ringkasan.pdf')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':laporan.export-pdf,view');

        // Export Requests
        Route::post('/export-requests', [ExportRequestController::class, 'store'])->name('export-requests.store');
        Route::patch('/export-requests/{exportRequest}/approve', [ExportRequestController::class, 'approve'])->name('export-requests.approve');
        Route::patch('/export-requests/{exportRequest}/reject', [ExportRequestController::class, 'reject'])->name('export-requests.reject');
        Route::get('/export-requests/{exportRequest}/download', [ExportRequestController::class, 'download'])->name('export-requests.download');

        // Master Data
        Route::resource('/kategori', KategoriKontenController::class)->except(['create', 'show', 'edit']);

        // AJAX Endpoint untuk Modal CRUD (Sesi Web)
        Route::apiResource('/ajax/kategori', \App\Http\Controllers\Api\KategoriController::class);

        // Manajemen Pengguna
        Route::get('/pengguna', [\App\Http\Controllers\ManajemenPenggunaController::class, 'index'])
            ->name('pengguna.index')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':manajemen-pengguna.lihat,view');
        Route::post('pengguna/admin', [\App\Http\Controllers\ManajemenPenggunaController::class, 'storeAdmin'])
            ->name('pengguna.admin.store')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':manajemen-pengguna.tambah,create');
        Route::post('/pengguna/user', [\App\Http\Controllers\ManajemenPenggunaController::class, 'storeUser'])
            ->name('pengguna.user.store')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':manajemen-pengguna.tambah-user,create');
            
        Route::put('/pengguna/{id}', [\App\Http\Controllers\ManajemenPenggunaController::class, 'update'])
            ->name('pengguna.update')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':manajemen-pengguna.edit,edit');
        Route::put('/pengguna/{id}/reset-password', [\App\Http\Controllers\ManajemenPenggunaController::class, 'resetPassword'])
            ->name('pengguna.reset')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':manajemen-pengguna.edit,edit');
        Route::patch('/pengguna/{id}/status', [\App\Http\Controllers\ManajemenPenggunaController::class, 'toggleStatus'])
            ->name('pengguna.status')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':manajemen-pengguna.edit,edit');
        Route::delete('/pengguna/{id}', [\App\Http\Controllers\ManajemenPenggunaController::class, 'destroy'])
            ->name('pengguna.destroy')
            ->middleware(\App\Http\Middleware\CheckPermission::class.':manajemen-pengguna.hapus,delete');

        // Master Data - Platform (read-only: 4 platform sudah di-seed lewat SQL,
        // halaman ini cuma utk lihat/toggle is_aktif, bukan CRUD penuh)
        Route::get('/platform', [PlatformController::class, 'index'])->name('master.platform.index');
        Route::patch('/platform/{platform}', [PlatformController::class, 'update'])->name('master.platform.update');
    });

});

// Catatan: pages.Report.laporan.blade.php diasumsikan @include di dalam
// pages.Dashboard.index.blade.php (tab "Laporan"/"Pusat Rekap Data"),
// karena datanya (metricsLaporan, laporanAgg, lapFilters) sudah disiapkan
// sekaligus di DashboardController::index() -> tidak butuh route terpisah.
// pages.Report.insight.blade.php tidak dipakai -> boleh dihapus filenya.