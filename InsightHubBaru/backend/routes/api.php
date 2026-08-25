<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RingkasanController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\InputController;

// Endpoint Publik (Bisa diakses tanpa token)

    Route::post('/login', [AuthController::class, 'login']);

// Endpoint Terproteksi (Wajib membawa Token Bearer)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [\App\Http\Controllers\Auth\ChangePasswordController::class, 'changePassword']);

    // Endpoint yang TERLINDUNGI dari user yang belum ganti password
    Route::middleware('force.change.password')->group(function () {
        
        // Ringkasan
        Route::get('/dashboard/ringkasan', [RingkasanController::class, 'index'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':ringkasan.lihat,view');
        Route::get('/dashboard/ringkasan/konten-terbaru', [RingkasanController::class, 'kontenTerbaru'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':ringkasan.lihat,view');

        // Kategori
        Route::apiResource('/kategori', KategoriController::class);

        // Platform
        Route::get('/platform', [PlatformController::class, 'index']);
        Route::put('/platform/{id}', [PlatformController::class, 'update']);

        // Input
        Route::post('/dashboard/input/instagram', [InputController::class, 'storeInstagram'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.instagram,create');
        Route::post('/dashboard/input/facebook',  [InputController::class, 'storeFacebook'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.facebook,create');
        Route::post('/dashboard/input/tiktok',    [InputController::class, 'storeTiktok'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.tiktok,create');
        Route::post('/dashboard/input/youtube/video',  [InputController::class, 'storeYoutubeVideo'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.youtube-video,create');
        Route::post('/dashboard/input/youtube/shorts', [InputController::class, 'storeYoutubeShorts'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.youtube-shorts,create');
        Route::post('/dashboard/input/youtube/live',   [InputController::class, 'storeYoutubeLive'])
            ->middleware(\App\Http\Middleware\CheckPermission::class.':input.youtube-live,create');

    });
});
