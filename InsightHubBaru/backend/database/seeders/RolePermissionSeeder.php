<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Auth\Role;
use App\Models\Auth\Menu;
use App\Models\Auth\MenuDetail;
use App\Models\Auth\Permission;
use App\Models\Auth\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ROLES
        $rolesData = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Akses penuh ke seluruh sistem', 'is_aktif' => true],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Akses operasional dashboard', 'is_aktif' => true],
            ['name' => 'User', 'slug' => 'user', 'description' => 'Akses terbatas', 'is_aktif' => true],
        ];

        foreach ($rolesData as $data) {
            Role::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();

        // 2. PERMISSIONS
        $permissionsData = [
            ['name' => 'View', 'slug' => 'view'],
            ['name' => 'Create', 'slug' => 'create'],
            ['name' => 'Edit', 'slug' => 'edit'],
            ['name' => 'Delete', 'slug' => 'delete'],
        ];

        foreach ($permissionsData as $data) {
            Permission::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // 3. MENUS
        $menusData = [
            ['name' => 'Ringkasan', 'slug' => 'ringkasan', 'urutan' => 1],
            ['name' => 'Analitik Konten', 'slug' => 'analitik-konten', 'urutan' => 2],
            ['name' => 'Input Data', 'slug' => 'input-data', 'urutan' => 3],
            ['name' => 'Laporan', 'slug' => 'laporan', 'urutan' => 4],
            ['name' => 'Profil', 'slug' => 'profil', 'urutan' => 5],
            ['name' => 'Manajemen Pengguna', 'slug' => 'manajemen-pengguna', 'urutan' => 6],
            ['name' => 'Manajemen Akses', 'slug' => 'manajemen-akses', 'urutan' => 7],
            ['name' => 'Aktivitas Pengguna', 'slug' => 'aktivitas-pengguna', 'urutan' => 8],
        ];

        foreach ($menusData as $data) {
            Menu::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // 4. MENU DETAILS & ROLE PERMISSIONS
        $menus = Menu::all()->keyBy('slug');

        $menuDetailsData = [
            'ringkasan' => [
                ['name' => 'Lihat Dashboard Ringkasan', 'slug' => 'ringkasan.lihat', 'urutan' => 1, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
            ],
            'analitik-konten' => [
                ['name' => 'Lihat Analitik Konten', 'slug' => 'analitik.lihat', 'urutan' => 1, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
            ],
            'input-data' => [
                ['name' => 'Lihat Riwayat Data', 'slug' => 'input.lihat', 'urutan' => 1, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Input Data Instagram', 'slug' => 'input.instagram', 'urutan' => 2, 'perms' => ['create' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Input Data Facebook', 'slug' => 'input.facebook', 'urutan' => 3, 'perms' => ['create' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Input Data TikTok', 'slug' => 'input.tiktok', 'urutan' => 4, 'perms' => ['create' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Input Data YouTube Video', 'slug' => 'input.youtube-video', 'urutan' => 5, 'perms' => ['create' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Input Data YouTube Shorts', 'slug' => 'input.youtube-shorts', 'urutan' => 6, 'perms' => ['create' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Input Data YouTube Live', 'slug' => 'input.youtube-live', 'urutan' => 7, 'perms' => ['create' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Edit Data Konten', 'slug' => 'input.edit', 'urutan' => 8, 'perms' => ['edit' => ['super-admin', 'admin']]],
                ['name' => 'Hapus Data Konten', 'slug' => 'input.hapus', 'urutan' => 9, 'perms' => ['delete' => ['super-admin', 'admin']]],
                ['name' => 'Import Data Konten', 'slug' => 'input.import', 'urutan' => 10, 'perms' => ['create' => ['super-admin', 'admin', 'user']]],
            ],
            'laporan' => [
                ['name' => 'Lihat Rekap Konten', 'slug' => 'laporan.lihat', 'urutan' => 1, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Export CSV', 'slug' => 'laporan.export-csv', 'urutan' => 2, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Export Excel', 'slug' => 'laporan.export-excel', 'urutan' => 3, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Export PDF', 'slug' => 'laporan.export-pdf', 'urutan' => 4, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
            ],
            'profil' => [
                ['name' => 'Lihat Profil', 'slug' => 'profil.lihat', 'urutan' => 1, 'perms' => ['view' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Edit Profil', 'slug' => 'profil.edit', 'urutan' => 2, 'perms' => ['edit' => ['super-admin', 'admin', 'user']]],
                ['name' => 'Ubah Password', 'slug' => 'profil.ubah-password', 'urutan' => 3, 'perms' => ['edit' => ['super-admin', 'admin', 'user']]],
            ],
            'manajemen-pengguna' => [
                ['name' => 'Lihat Pengguna', 'slug' => 'manajemen-pengguna.lihat', 'urutan' => 1, 'perms' => ['view' => ['super-admin', 'admin']]],
                // Eksklusif Super Admin untuk membuat Admin
                ['name' => 'Tambah Pengguna (Admin)', 'slug' => 'manajemen-pengguna.tambah', 'urutan' => 2, 'perms' => ['create' => ['super-admin']]],
                ['name' => 'Edit Data Pengguna', 'slug' => 'manajemen-pengguna.edit', 'urutan' => 3, 'perms' => ['edit' => ['super-admin', 'admin']]],
                ['name' => 'Nonaktifkan Pengguna', 'slug' => 'manajemen-pengguna.nonaktifkan', 'urutan' => 4, 'perms' => ['edit' => ['super-admin', 'admin']]],
                ['name' => 'Hapus Akun Pengguna', 'slug' => 'manajemen-pengguna.hapus', 'urutan' => 5, 'perms' => ['delete' => ['super-admin', 'admin']]],
                ['name' => 'Reset Password Pengguna', 'slug' => 'manajemen-pengguna.reset-password', 'urutan' => 6, 'perms' => ['edit' => ['super-admin', 'admin']]],
                // Admin dapat membuat User
                ['name' => 'Tambah Pengguna (User)', 'slug' => 'manajemen-pengguna.tambah-user', 'urutan' => 7, 'perms' => ['create' => ['super-admin', 'admin']]],
            ],
            'manajemen-akses' => [
                ['name' => 'Kelola Role', 'slug' => 'manajemen-akses.kelola-role', 'urutan' => 1, 'perms' => ['view' => ['super-admin'], 'create' => ['super-admin'], 'edit' => ['super-admin'], 'delete' => ['super-admin']]],
                ['name' => 'Kelola Permission', 'slug' => 'manajemen-akses.kelola-permission', 'urutan' => 2, 'perms' => ['view' => ['super-admin'], 'create' => ['super-admin'], 'edit' => ['super-admin'], 'delete' => ['super-admin']]],
            ],
            'aktivitas-pengguna' => [
                ['name' => 'Lihat Aktivitas Pengguna', 'slug' => 'aktivitas-pengguna.lihat', 'urutan' => 1, 'perms' => ['view' => ['super-admin']]],
            ]
        ];

        DB::table('role_permissions')->delete();

        foreach ($menuDetailsData as $menuSlug => $details) {
            $menu = $menus[$menuSlug];
            foreach ($details as $detail) {
                $menuDetail = MenuDetail::updateOrCreate(
                    ['slug' => $detail['slug']],
                    [
                        'menu_id' => $menu->id,
                        'name' => $detail['name'],
                        'urutan' => $detail['urutan'],
                    ]
                );

                foreach ($detail['perms'] as $permSlug => $roles) {
                    $permission = Permission::where('slug', $permSlug)->first();
                    foreach ($roles as $roleSlug) {
                        $role = Role::where('slug', $roleSlug)->first();
                        
                        DB::table('role_permissions')->insert([
                            'role_id' => $role->id,
                            'menu_detail_id' => $menuDetail->id,
                            'permission_id' => $permission->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // 5. MAPPING EXISTING USERS
        $users = User::all();
        foreach ($users as $user) {
            $isRoleAdmin = (strtolower($user->role) === 'admin');
            $isNameAdmin = (strtolower($user->name) === 'admin' || strtolower($user->name) === 'admin sovie');
            $isEmailAdmin = (strpos(strtolower($user->email), 'admin') !== false);
            
            if ($isRoleAdmin || $isNameAdmin || $isEmailAdmin) {
                $user->role_id = $adminRole->id;
            } else {
                $user->role_id = $userRole->id;
            }
            $user->save();
        }
    }
}
