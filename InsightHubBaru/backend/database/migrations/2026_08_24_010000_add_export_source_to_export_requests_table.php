<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom `export_source` ke tabel `export_requests`
     * untuk membedakan sumber export secara eksplisit.
     * Nilai default 'laporan' agar semua record lama otomatis dianggap Laporan.
     */
    public function up(): void
    {
        Schema::table('export_requests', function (Blueprint $table) {
            // Tambahkan kolom export_source setelah kolom type
            // Menggunakan nvarchar(50) yang kompatibel dengan SQL Server
            // default 'laporan' agar data lama otomatis menjadi 'laporan'
            $table->string('export_source', 50)->default('laporan')->after('type');
        });

        // Backfill: jika ada record lama yang di dalam JSON filters
        // memiliki export_source = 'ringkasan', update kolom baru dengan benar.
        // Hanya dilakukan untuk record yang dapat diverifikasi secara eksplisit.
        // Record yang tidak memiliki filters.export_source = 'ringkasan'
        // tetap default ke 'laporan'.
        DB::statement("
            UPDATE export_requests
            SET export_source = 'ringkasan'
            WHERE JSON_VALUE(filters, '$.export_source') = 'ringkasan'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('export_requests', function (Blueprint $table) {
            $table->dropColumn('export_source');
        });
    }
};
