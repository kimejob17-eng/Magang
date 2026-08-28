<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Menu;
use App\Models\Auth\Permission;
use App\Models\Auth\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleMappingController extends Controller
{
    /**
     * Display the Role Mapping matrix for a specific user.
     */
    public function index(Request $request)
    {
        // Pengamanan ketat: Hanya Super Admin
        abort_if(auth()->user()->role !== 'super-admin', 403, 'Akses ditolak. Hanya Super Admin yang dapat mengelola hak akses.');

        // Mengambil semua user kecuali akun Super Admin saat ini (atau tampilkan semua user)
        $users = User::orderBy('name', 'asc')->get();

        // Akun pengguna terpilih untuk konfigurasi hak akses
        $selectedUserId = $request->query('user_id');
        if ($selectedUserId) {
            $selectedUser = User::findOrFail($selectedUserId);
        } else {
            // Default ke user pertama selain diri sendiri jika ada
            $selectedUser = User::where('id', '!=', auth()->id())->orderBy('name', 'asc')->first() ?: auth()->user();
        }

        // Ambil semua menu beserta detailnya
        $menus = Menu::with(['details' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])->orderBy('urutan', 'asc')->get();

        // Ambil semua tipe permission
        $permissions = Permission::all();

        // Muat mapping yang sedang aktif untuk role_id milik user terpilih
        $activeMappings = [];
        $isCustomRole = false;

        if ($selectedUser && $selectedUser->role_id) {
            $activeMappings = RolePermission::where('role_id', $selectedUser->role_id)
                ->get()
                ->groupBy('menu_detail_id')
                ->map(function ($items) {
                    return $items->pluck('permission_id')->toArray();
                })
                ->toArray();

            // Cek apakah user menggunakan custom role (ditandai dengan awalan 'user-custom-')
            if ($selectedUser->roleModel && str_starts_with($selectedUser->roleModel->slug, 'user-custom-')) {
                $isCustomRole = true;
            }
        }

        return view('pages.Master.role_mapping', compact('users', 'selectedUser', 'menus', 'permissions', 'activeMappings', 'isCustomRole'));
    }

    /**
     * Update the permissions for the selected user account.
     */
    public function update(Request $request)
    {
        // Pengamanan ketat: Hanya Super Admin
        abort_if(auth()->user()->role !== 'super-admin', 403, 'Akses ditolak. Hanya Super Admin yang dapat mengubah hak akses.');

        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'mapping'   => 'nullable|array',
            'mapping.*' => 'nullable|array',
        ]);

        $userId = $validated['user_id'];
        $mapping = $validated['mapping'] ?? [];

        $user = User::findOrFail($userId);

        DB::transaction(function () use ($user, $mapping) {
            $role = $user->roleModel;
            $isDefaultRole = !$role || in_array($role->slug, ['super-admin', 'admin', 'user']);

            // Jika user masih menggunakan role default, buatkan Custom Role baru khusus untuknya
            if ($isDefaultRole) {
                $customRole = Role::create([
                    'name'        => 'Akses: ' . $user->name . ' (' . $user->username . ')',
                    'slug'        => 'user-custom-' . $user->id,
                    'description' => 'Izin akses kustom khusus akun ' . $user->name,
                    'is_aktif'    => true,
                ]);

                $roleId = $customRole->id;

                // Kaitkan user ke role kustom baru
                $user->role_id = $roleId;
                $user->save();
            } else {
                $roleId = $role->id;
            }

            // Bersihkan data izin lama untuk role_id tersebut
            RolePermission::where('role_id', $roleId)->delete();

            // Masukkan data izin baru dari checkbox
            $insertData = [];
            foreach ($mapping as $menuDetailId => $permissionIds) {
                foreach (array_keys($permissionIds) as $permissionId) {
                    $insertData[] = [
                        'role_id'        => $roleId,
                        'menu_detail_id' => $menuDetailId,
                        'permission_id'  => $permissionId,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];
                }
            }

            if (!empty($insertData)) {
                RolePermission::insert($insertData);
            }
        });

        return redirect()->route('role-mapping.index', ['user_id' => $user->id])
            ->with('success', 'Hak akses untuk akun ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Reset user permissions back to the default role configuration.
     */
    public function reset(Request $request)
    {
        // Pengamanan ketat: Hanya Super Admin
        abort_if(auth()->user()->role !== 'super-admin', 403, 'Akses ditolak.');

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $role = $user->roleModel;

        DB::transaction(function () use ($user, $role) {
            // Jika role saat ini kustom, hapus data pemetaan izin dan hapus role kustom tersebut
            if ($role && str_starts_with($role->slug, 'user-custom-')) {
                RolePermission::where('role_id', $role->id)->delete();
                $user->role_id = null;
                $user->save();
                $role->delete();
            }

            // Kembalikan ke default role_id berdasarkan kolom string `role`
            $defaultRoleSlug = $user->role; // 'admin' atau 'user'
            $defaultRole = Role::where('slug', $defaultRoleSlug)->first();
            if ($defaultRole) {
                $user->role_id = $defaultRole->id;
                $user->save();
            }
        });

        return redirect()->route('role-mapping.index', ['user_id' => $user->id])
            ->with('success', 'Hak akses akun ' . $user->name . ' berhasil di-reset kembali ke pengaturan default role.');
    }
}
