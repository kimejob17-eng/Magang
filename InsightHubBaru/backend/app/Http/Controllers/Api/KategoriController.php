<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konten\KategoriKonten;
use App\Models\Pengaturan\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KategoriController extends Controller
{
    /**
     * GET /api/kategori
     * Mengambil seluruh daftar kategori, diurutkan berdasarkan nama platform lalu nama kategori.
     */
    public function index(): JsonResponse
    {
        $kategoris = KategoriKonten::query()
            ->join('platform', 'platform.id', '=', 'kategori_konten.platform_id')
            ->orderBy('platform.nama')
            ->orderBy('kategori_konten.nama')
            ->select(
                'kategori_konten.*',
                'platform.nama as platform_nama',
                'platform.slug as platform_slug'
            )
            ->get();

        // Alias 'dibuat_pada'/'diperbarui_pada' (nama kolom asli di DB) menjadi
        // 'created_at'/'updated_at' agar konsisten dengan format response
        // yang sudah didokumentasikan di dokumentasi API.
        $kategoris = $kategoris->map(function ($item) {
            $item->created_at = $item->dibuat_pada;
            $item->updated_at = $item->diperbarui_pada;
            unset($item->dibuat_pada, $item->diperbarui_pada);
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil',
            'data'    => $kategoris,
        ], 200);
    }

    /**
     * POST /api/kategori
     * Menambah kategori baru. Field 'platform' berupa slug, akan diresolusi ke platform_id.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'platform'      => ['required', 'string', 'exists:platform,slug'],
            'nama_kategori' => ['required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $platform = Platform::where('slug', $validated['platform'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Platform dengan slug tersebut tidak ditemukan.',
            ], 422);
        }

        $kategori = KategoriKonten::create([
            'platform_id' => $platform->id,
            'nama'        => $validated['nama_kategori'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data'    => $kategori,
        ], 201);
    }

    /**
     * PUT /api/kategori/{id}
     * Memperbarui kategori yang sudah ada.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $kategori = KategoriKonten::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'platform'      => ['required', 'string', 'exists:platform,slug'],
            'nama_kategori' => ['required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $platform = Platform::where('slug', $validated['platform'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Platform dengan slug tersebut tidak ditemukan.',
            ], 422);
        }

        $kategori->update([
            'platform_id' => $platform->id,
            'nama'        => $validated['nama_kategori'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => $kategori,
        ], 200);
    }

    /**
     * DELETE /api/kategori/{id}
     * Menghapus kategori, dengan proteksi integritas jika masih terikat data metrik.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $kategori = KategoriKonten::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        if ($kategori->hasRelatedMetrics()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih digunakan pada data metrik.',
            ], 409);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ], 200);
    }
}