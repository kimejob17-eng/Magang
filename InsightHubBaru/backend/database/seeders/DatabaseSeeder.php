<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memastikan akun testing (development only) tersedia.
        $this->call(RolePermissionSeeder::class);

        $roles = \App\Models\Auth\Role::all()->keyBy('slug');

        // Akun Super Admin
        User::firstOrCreate(['email' => 'superadmin@sovie.com'], [
            'name'     => 'Super Admin Testing',
            'username' => 'superadmin',
            'password' => Hash::make('password123'),
            'role'     => 'super-admin',
            'role_id'  => $roles['super-admin']->id ?? null,
            'must_change_password' => false,
        ]);

        // Akun Admin
        User::firstOrCreate(['email' => 'admin@sovie.com'], [
            'name'     => 'Admin SOVIE',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'role_id'  => $roles['admin']->id ?? null,
            'must_change_password' => false,
        ]);

        // Akun User
        User::firstOrCreate(['email' => 'user@sovie.com'], [
            'name'     => 'User Testing',
            'username' => 'user',
            'password' => Hash::make('password123'),
            'role'     => 'user',
            'role_id'  => $roles['user']->id ?? null,
            'must_change_password' => false,
        ]);
    }
}