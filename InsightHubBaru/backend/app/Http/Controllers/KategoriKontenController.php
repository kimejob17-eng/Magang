<?php

namespace App\Http\Controllers;

use App\Models\KategoriKonten;
use App\Models\Platform;
use Illuminate\Http\Request;

class KategoriKontenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Diurutkan per nama platform lalu nama kategori (dulu: orderBy('platform')).
        // Karena 'platform' sekarang FK (platform_id), butuh join ke tabel platform.
        $kategoris = KategoriKonten::query()
            ->join('platform', 'platform.id', '=', 'kategori_konten.platform_id')
            ->orderBy('platform.nama')
            ->orderBy('kategori_konten.nama')
            ->select('kategori_konten.*', 'platform.nama as platform_nama', 'platform.slug as platform_slug')
            ->get();

        return view('pages.Master.kategori', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Form tetap kirim 'platform' sbg string slug (facebook/instagram/tiktok/youtube)
        // dan 'nama_kategori' sbg nama kategori -> di sini baru dipetakan ke
        // platform_id + kolom 'nama' sesuai schema v6.
        $validated = $request->validate([
            'platform'      => 'required|string|exists:platform,slug',
            'nama_kategori' => 'required|string|max:100',
        ]);

        $platform = Platform::where('slug', $validated['platform'])->firstOrFail();

        KategoriKonten::create([
            'nama'        => $validated['nama_kategori'],
            'platform_id' => $platform->id,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'platform'      => 'required|string|exists:platform,slug',
            'nama_kategori' => 'required|string|max:100',
        ]);

        $platform = Platform::where('slug', $validated['platform'])->firstOrFail();
        $kategori = KategoriKonten::findOrFail($id);

        $kategori->update([
            'nama'        => $validated['nama_kategori'],
            'platform_id' => $platform->id,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = KategoriKonten::findOrFail($id);

        // Pengganti $kategori->metrics()->exists() -> cek ke tabel konten
        // yang sesuai dengan platform kategori ini (lihat KategoriKonten::hasRelatedMetrics()).
        if ($kategori->hasRelatedMetrics()) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan pada data metrik.');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}