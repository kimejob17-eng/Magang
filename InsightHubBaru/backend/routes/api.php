<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RingkasanController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\InputController;

// Endpoint Publik (Bisa diakses tanpa token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Endpoint Terproteksi (Wajib membawa Token Bearer)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// ─────────────────────────────────────────
// Endpoint Ringkasan Terproteksi (Wajib Bearer Token)
// ─────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/ringkasan', [RingkasanController::class, 'index']);
    Route::get('/dashboard/ringkasan/konten-terbaru', [RingkasanController::class, 'kontenTerbaru']);
});

Route::middleware('auth:sanctum')->group(function () {
 
    Route::apiResource('/kategori', KategoriController::class);
 
    // apiResource otomatis mendaftarkan 4 route berikut:
    // GET    /api/kategori           -> index()
    // POST   /api/kategori           -> store()
    // PUT    /api/kategori/{id}      -> update()
    // DELETE /api/kategori/{id}      -> destroy()
 
});

Route::middleware('auth:sanctum')->group(function () {
 
    // Deklarasi eksplisit (BUKAN Route::apiResource) — sengaja hanya 2 route.
    // Ini mencegah route store()/destroy() otomatis terbentuk, karena data
    // platform bersifat fixed dari seed database dan tidak boleh
    // ditambah/dihapus lewat API.
    Route::get('/platform', [PlatformController::class, 'index']);
    Route::put('/platform/{id}', [PlatformController::class, 'update']);
 
});

Route::middleware('auth:sanctum')->group(function () {
 
    Route::post('/dashboard/input/instagram', [InputController::class, 'storeInstagram']);
    Route::post('/dashboard/input/facebook',  [InputController::class, 'storeFacebook']);
    Route::post('/dashboard/input/tiktok',    [InputController::class, 'storeTiktok']);
 
    // YouTube dipecah jadi 3 endpoint terpisah (Video/Shorts/Live)
    // karena masing-masing punya tabel dan model Eloquent sendiri.
    Route::post('/dashboard/input/youtube/video',  [InputController::class, 'storeYoutubeVideo']);
    Route::post('/dashboard/input/youtube/shorts', [InputController::class, 'storeYoutubeShorts']);
    Route::post('/dashboard/input/youtube/live',   [InputController::class, 'storeYoutubeLive']);
 
});
