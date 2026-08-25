<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PlatformController extends Controller
{
    /**
     * GET /api/platform
     * Mengambil seluruh daftar platform, diurutkan berdasarkan abjad (nama).
     *
     * Catatan: Platform bersifat fixed dari seed database (Facebook, Instagram,
     * TikTok, YouTube). Tidak ada method store()/destroy() secara sengaja,
     * sesuai kaidah immutable core pada dokumentasi.
     */
    public function index(): JsonResponse
    {
        $platforms = Platform::orderBy('nama')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar platform berhasil diambil',
            'data'    => $platforms,
        ], 200);
    }

    /**
     * PUT /api/platform/{id}
     * Melakukan toggle nilai keaktifan (is_aktif) suatu platform.
     * Hanya field 'is_aktif' yang dapat diubah — nama dan slug bersifat tetap.
     */
    public function update(Request $request, string $platform): JsonResponse
    {
        try {
            $row = Platform::findOrFail($platform);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Platform tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'is_aktif' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $row->update([
            'is_aktif' => $validated['is_aktif'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status platform berhasil diperbarui.',
            'data'    => $row,
        ], 200);
    }
}