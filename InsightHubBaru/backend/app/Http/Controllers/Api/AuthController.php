<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * POST /api/login
     * Login pengguna dan berikan Token Bearer.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'role'     => 'nullable|string|in:user,admin',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial login tidak cocok.'
            ], 401);
        }

        // Validasi role jika dikirim oleh client
        if (!empty($validated['role']) && strtolower($user->role) !== strtolower($validated['role'])) {
            return response()->json([
                'success' => false,
                'message' => 'Role akun tidak sesuai.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => $user->must_change_password ? 'Login berhasil. Anda wajib mengganti kata sandi.' : 'Login berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'require_password_change' => $user->must_change_password,
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        ], 200);
    }

    /**
     * POST /api/logout
     * Revoke token saat ini.
     */
    public function logout(Request $request)
    {
        // Revoke token yang sedang dipakai untuk request ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token berhasil dihapus (Logout berhasil)'
        ], 200);
    }
}
