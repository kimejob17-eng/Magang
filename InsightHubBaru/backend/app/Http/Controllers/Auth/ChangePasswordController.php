<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ChangePasswordController extends Controller
{
    /**
     * Menampilkan form ganti password wajib
     */
    public function showChangePasswordForm(Request $request)
    {
        return view('auth.force-change-password');
    }

    /**
     * Memproses penggantian password wajib (Web & API)
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kata sandi saat ini tidak sesuai.'
                ], 400);
            }
            return back()->with('error', 'Kata sandi saat ini tidak sesuai.');
        }

        // Update password dan matikan flag
        $user->update([
            'password' => Hash::make($data['password']),
            'must_change_password' => false,
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Kata sandi berhasil diubah. Anda sekarang memiliki akses penuh.'
            ], 200);
        }

        return redirect()->route('dashboard')->with('success', 'Kata sandi berhasil diubah.');
    }
}
