<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManajemenPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Strict Authorization: Hanya Super Admin & Admin
        abort_if(!in_array(auth()->user()->role, ['super-admin', 'admin']), 403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat halaman ini.');

        $query = User::with('roleModel')->orderBy('id', 'desc');

        // 1. RBAC Query Scoping
        if (auth()->user()->role === 'admin') {
            // Admin hanya bisa melihat pengguna/User
            $query->where('role', 'user');
        } else {
            // Super Admin bisa melihat Admin dan pengguna/User
            $query->whereIn('role', ['admin', 'user']);
        }

        // 2. Search Filter (nama, username, email)
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Role Filter (Hanya berpengaruh bagi Super Admin jika milih Admin)
        if ($role = $request->query('role')) {
            if ($role === 'admin' && auth()->user()->role === 'super-admin') {
                $query->where('role', 'admin');
            } elseif ($role === 'user') {
                $query->where('role', 'user');
            }
        }

        // 4. Pagination
        $employees = $query->paginate(10)->withQueryString();

        return view('pages.Master.pengguna', compact('employees'));
    }

    /**
     * Store a newly created Admin in storage.
     */
    public function storeAdmin(Request $request)
    {
        // Strict Authorization: Hanya Super Admin yang boleh membuat Admin
        abort_if(auth()->user()->role !== 'super-admin', 403, 'Akses ditolak. Hanya Super Admin yang dapat membuat akun Admin.');

        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $adminRole = Role::where('slug', 'admin')->firstOrFail();

        User::create([
            'name'                 => $validated['name'],
            'username'             => $validated['username'],
            'email'                => $validated['email'],
            'password'             => Hash::make($validated['password']),
            'role'                 => 'admin',
            'role_id'              => $adminRole->id,
            'must_change_password' => false, // Set to false since they created their own password
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Akun Admin berhasil dibuat.');
    }

    /**
     * Store a newly created User in storage (Created by Admin/Super Admin).
     */
    public function storeUser(Request $request)
    {
        // Strict Authorization: Hanya Super Admin & Admin yang boleh membuat User
        abort_if(!in_array(auth()->user()->role, ['super-admin', 'admin']), 403, 'Akses ditolak. Anda tidak memiliki izin untuk membuat akun Pengguna.');

        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userRole = Role::where('slug', 'user')->firstOrFail();

        User::create([
            'name'                 => $validated['name'],
            'username'             => $validated['username'],
            'email'                => $validated['email'],
            'password'             => Hash::make($validated['password']),
            'role'                 => 'user',
            'role_id'              => $userRole->id,
            'must_change_password' => false,
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Akun User berhasil dibuat.');
    }

    /**
     * RBAC Check helper to ensure users can't touch what they shouldn't.
     */
    private function checkTargetUserAccess(User $targetUser)
    {
        // User cannot target themselves to prevent self-lockout or deletion
        abort_if(auth()->id() === $targetUser->id, 403, 'Anda tidak dapat mengubah atau menghapus akun Anda sendiri dari halaman ini.');

        // Admin can only target Users
        if (auth()->user()->role === 'admin') {
            abort_if($targetUser->role !== 'user', 403, 'Akses ditolak. Anda hanya diizinkan mengelola akun Pengguna.');
        }

        // Only super-admin and admin can access this page overall
        abort_if(!in_array(auth()->user()->role, ['super-admin', 'admin']), 403, 'Akses ditolak.');
    }

    /**
     * Update existing user info
     */
    public function update(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $this->checkTargetUserAccess($targetUser);

        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($targetUser->id)],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($targetUser->id)],
        ]);

        $targetUser->update($validated);

        return redirect()->route('pengguna.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * Force reset password for a user
     */
    public function resetPassword(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $this->checkTargetUserAccess($targetUser);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $targetUser->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => true, // Force user to change password on next login
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Password berhasil di-reset. Pengguna wajib mengganti password saat login berikutnya.');
    }

    /**
     * Toggle active/inactive status.
     * As per database constraint, 'is_active' does not exist. We simulate or block it.
     */
    public function toggleStatus($id)
    {
        $targetUser = User::findOrFail($id);
        $this->checkTargetUserAccess($targetUser);

        // Fallback since the users table doesn't have an is_active column.
        return redirect()->route('pengguna.index')->with('error', 'Gagal: Fitur nonaktifkan akun belum didukung oleh struktur database saat ini. Silakan hubungi pengembang untuk migrasi database.');
    }

    /**
     * Delete user account
     */
    public function destroy($id)
    {
        $targetUser = User::findOrFail($id);
        $this->checkTargetUserAccess($targetUser);

        $targetUser->delete();

        return redirect()->route('pengguna.index')->with('success', 'Akun berhasil dihapus secara permanen.');
    }
}
