<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom username sebagai nullable terlebih dahulu TANPA unique index
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
        });

        // 2. Isi username berdasarkan email sebelum '@' (Sintaks SQL Server)
        DB::statement("
            UPDATE users 
            SET username = SUBSTRING(email, 1, CHARINDEX('@', email) - 1) 
            WHERE username IS NULL AND CHARINDEX('@', email) > 0
        ");

        // 3. Pastikan username unique. Handle duplikasi menggunakan CTE (SQL Server)
        DB::statement("
            WITH CTE AS (
                SELECT id, username,
                       ROW_NUMBER() OVER(PARTITION BY username ORDER BY id) as rn
                FROM users
                WHERE username IS NOT NULL
            )
            UPDATE u 
            SET u.username = c.username + CAST(c.rn AS VARCHAR(10))
            FROM users u
            INNER JOIN CTE c ON u.id = c.id
            WHERE c.rn > 1
        ");

        // 4 & 5. Ubah menjadi NOT NULL dan tambahkan unique index
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
            $table->unique('username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
