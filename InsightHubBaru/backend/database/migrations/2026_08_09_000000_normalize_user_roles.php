<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalisasi nilai kolom role ke lowercase ('user' atau 'admin').
     * Nilai lama seperti 'User', 'viewer', 'Admin' akan dikonversi.
     */
    public function up(): void
    {
        // Konversi 'User' (kapital) dan 'viewer' → 'user'
        DB::table('users')
            ->whereIn('role', ['User', 'viewer', 'Viewer'])
            ->update(['role' => 'user']);

        // Konversi 'Admin' (kapital) → 'admin'
        DB::table('users')
            ->where('role', 'Admin')
            ->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // Tidak diperlukan rollback karena ini normalisasi data
    }
};
