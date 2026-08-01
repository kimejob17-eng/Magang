<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::orderBy('nama')->get();

        return view('pages.Master.platform', compact('platforms'));
    }

    /**
     * Cuma toggle is_aktif (4 platform sudah fix dari seed data schema v6,
     * tidak ada create/delete platform baru dari UI).
     */
    public function update(Request $request, string $platform)
    {
        $validated = $request->validate([
            'is_aktif' => 'required|boolean',
        ]);

        $row = Platform::findOrFail($platform);
        $row->update(['is_aktif' => $validated['is_aktif']]);

        return redirect()->route('master.platform.index')->with('success', 'Status platform berhasil diperbarui.');
    }
}