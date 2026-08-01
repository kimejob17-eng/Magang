<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('pages.Profile.profile', ['user' => $request->user()]);
    }

    /**
     * Update Informasi Pribadi.
     * Form kirim field: name, email, phone, location, avatar
     * -> disimpan ke kolom asli: nama_lengkap, email, nomor_telepon, lokasi, foto_profil
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'location' => 'nullable|string|max:100',
            'avatar'   => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'location' => $validated['location'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Perubahan berhasil disimpan.');
    }

    /**
     * Ubah Password.
     * Form kirim: current_password, password, password_confirmation
     * -> dicek & disimpan ke kolom asli: password
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()
                ->with('error', 'Kata sandi saat ini tidak sesuai.')
                ->with('show_password_modal', true);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}