<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // =========================================================
        // 1. PLATFORM
        // =========================================================
        if (!Schema::hasTable('platform')) {
            Schema::create('platform', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 50);
                $table->string('slug', 50)->unique();
                $table->boolean('is_aktif')->default(true);
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 2. KATEGORI KONTEN
        // =========================================================
        if (!Schema::hasTable('kategori_konten')) {
            Schema::create('kategori_konten', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100);
                $table->foreignId('platform_id')
                    ->constrained('platform')
                    ->cascadeOnDelete();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 3. KONTEN FACEBOOK
        // =========================================================
        if (!Schema::hasTable('konten_facebook')) {
            Schema::create('konten_facebook', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')
                    ->constrained('kategori_konten')
                    ->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('jenis_konten', 50);
                $table->string('tautan', 500)->nullable();
                $table->date('tanggal_tayang');
                $table->integer('views')->default(0);
                $table->integer('likes')->default(0);
                $table->integer('comments')->default(0);
                $table->integer('shares')->default(0);
                $table->integer('saves')->default(0);
                $table->string('diinput_oleh', 150)->nullable();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 4. KONTEN INSTAGRAM
        // =========================================================
        if (!Schema::hasTable('konten_instagram')) {
            Schema::create('konten_instagram', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')
                    ->constrained('kategori_konten')
                    ->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('jenis_konten', 50);
                $table->string('tautan', 500)->nullable();
                $table->date('tanggal_tayang');
                $table->integer('reach')->default(0);
                $table->integer('views')->default(0);
                $table->integer('likes')->default(0);
                $table->integer('comments')->default(0);
                $table->integer('shares')->default(0);
                $table->integer('repost')->default(0);
                $table->integer('saves')->default(0);
                $table->string('diinput_oleh', 150)->nullable();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 5. KONTEN TIKTOK
        // =========================================================
        if (!Schema::hasTable('konten_tiktok')) {
            Schema::create('konten_tiktok', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')
                    ->constrained('kategori_konten')
                    ->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('jenis_konten', 50);
                $table->string('tautan', 500)->nullable();
                $table->date('tanggal_tayang');
                $table->integer('tayangan')->default(0);
                $table->integer('total_interaksi')->default(0);
                $table->integer('likes')->default(0);
                $table->integer('comments')->default(0);
                $table->integer('shares')->default(0);
                $table->integer('saves')->default(0);
                $table->string('diinput_oleh', 150)->nullable();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 6. KONTEN YOUTUBE LIVE
        // =========================================================
        if (!Schema::hasTable('konten_youtube_live')) {
            Schema::create('konten_youtube_live', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')
                    ->constrained('kategori_konten')
                    ->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('tautan', 500)->nullable();
                $table->date('tanggal_tayang');
                $table->integer('jumlah_penayangan')->default(0);
                $table->integer('penambahan_subscriber')->default(0);
                $table->integer('penonton_puncak')->default(0);
                $table->integer('likes')->default(0);
                $table->integer('comments')->default(0);
                $table->integer('shares')->default(0);
                $table->string('diinput_oleh', 150)->nullable();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 7. KONTEN YOUTUBE VIDEO
        // =========================================================
        if (!Schema::hasTable('konten_youtube_video')) {
            Schema::create('konten_youtube_video', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')
                    ->constrained('kategori_konten')
                    ->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('tautan', 500)->nullable();
                $table->date('tanggal_tayang');
                $table->integer('jumlah_penayangan')->default(0);
                $table->integer('penambahan_subscriber')->default(0);
                $table->integer('likes')->default(0);
                $table->integer('comments')->default(0);
                $table->integer('shares')->default(0);
                $table->string('diinput_oleh', 150)->nullable();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 8. KONTEN YOUTUBE SHORTS
        // =========================================================
        if (!Schema::hasTable('konten_youtube_shorts')) {
            Schema::create('konten_youtube_shorts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')
                    ->constrained('kategori_konten')
                    ->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('tautan', 500)->nullable();
                $table->date('tanggal_tayang');
                $table->integer('jumlah_penayangan')->default(0);
                $table->integer('penambahan_subscriber')->default(0);
                $table->integer('likes')->default(0);
                $table->integer('comments')->default(0);
                $table->integer('shares')->default(0);
                $table->integer('repost')->default(0);
                $table->string('diinput_oleh', 150)->nullable();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // =========================================================
        // 8B. PERBAIKI DATABASE LAMA
        // =========================================================
        if (
            Schema::hasTable('konten_youtube_shorts') &&
            !Schema::hasColumn('konten_youtube_shorts', 'shares')
        ) {
            Schema::table('konten_youtube_shorts', function (Blueprint $table) {
                $table->integer('shares')
                    ->default(0)
                    ->after('comments');
            });
        }

        // =========================================================
        // 9. VIEW REKAP KONTEN
        // =========================================================
        $viewSql = "
            CREATE OR REPLACE VIEW v_rekap_konten AS
            -- FACEBOOK
            SELECT 
                CAST(CONCAT('FB-', kf.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST(p.slug AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform_slug,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform_nama,
                kk.nama AS kategori,
                kf.judul,
                kf.jenis_konten,
                kf.tautan,
                kf.tanggal_tayang AS tgl_upload,
                kf.views AS reach,
                (COALESCE(kf.likes, 0) + COALESCE(kf.comments, 0) + COALESCE(kf.shares, 0)) AS total_interaksi,
                kf.likes AS suka, kf.comments AS komentar, kf.shares AS dibagikan,
                0 AS penambahan_subscriber,
                0 AS penonton_puncak,
                kf.diinput_oleh,
                kf.dibuat_pada
            FROM konten_facebook kf
            JOIN kategori_konten kk ON kf.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- INSTAGRAM
            SELECT 
                CAST(CONCAT('IG-', ki.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci,
                p.slug, p.nama, kk.nama, ki.judul, ki.jenis_konten, ki.tautan, ki.tanggal_tayang,
                ki.reach,
                (COALESCE(ki.likes, 0) + COALESCE(ki.comments, 0) + COALESCE(ki.shares, 0) + COALESCE(ki.repost, 0)),
                ki.likes, ki.comments, ki.shares,
                0, 0, ki.diinput_oleh, ki.dibuat_pada
            FROM konten_instagram ki
            JOIN kategori_konten kk ON ki.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- TIKTOK
            SELECT 
                CAST(CONCAT('TK-', kt.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci,
                p.slug, p.nama, kk.nama, kt.judul, kt.jenis_konten, kt.tautan, kt.tanggal_tayang,
                kt.tayangan, kt.total_interaksi, kt.likes, kt.comments, kt.shares,
                0, 0, kt.diinput_oleh, kt.dibuat_pada
            FROM konten_tiktok kt
            JOIN kategori_konten kk ON kt.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- YOUTUBE VIDEO
            SELECT 
                CAST(CONCAT('YV-', yv.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci,
                p.slug, p.nama, kk.nama, yv.judul, CAST('Video' AS CHAR(50)) COLLATE utf8mb4_unicode_ci, yv.tautan, yv.tanggal_tayang,
                yv.jumlah_penayangan, (COALESCE(yv.likes, 0) + COALESCE(yv.comments, 0) + COALESCE(yv.shares, 0)), yv.likes, yv.comments, yv.shares,
                yv.penambahan_subscriber, 0, yv.diinput_oleh, yv.dibuat_pada
            FROM konten_youtube_video yv
            JOIN kategori_konten kk ON yv.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- YOUTUBE SHORTS
            SELECT 
                CAST(CONCAT('YS-', ys.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci,
                p.slug, p.nama, kk.nama, ys.judul, CAST('Shorts' AS CHAR(50)) COLLATE utf8mb4_unicode_ci, ys.tautan, ys.tanggal_tayang,
                ys.jumlah_penayangan, (COALESCE(ys.likes, 0) + COALESCE(ys.comments, 0) + COALESCE(ys.shares, 0) + COALESCE(ys.repost, 0)), ys.likes, ys.comments, ys.shares,
                ys.penambahan_subscriber, 0, ys.diinput_oleh, ys.dibuat_pada
            FROM konten_youtube_shorts ys
            JOIN kategori_konten kk ON ys.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- YOUTUBE LIVE
            SELECT 
                CAST(CONCAT('YL-', yl.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci,
                p.slug, p.nama, kk.nama, yl.judul, CAST('Live' AS CHAR(50)) COLLATE utf8mb4_unicode_ci, yl.tautan, yl.tanggal_tayang,
                yl.jumlah_penayangan, (COALESCE(yl.likes, 0) + COALESCE(yl.comments, 0) + COALESCE(yl.shares, 0)), yl.likes, yl.comments, yl.shares,
                yl.penambahan_subscriber, yl.penonton_puncak, yl.diinput_oleh, yl.dibuat_pada
            FROM konten_youtube_live yl
            JOIN kategori_konten kk ON yl.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
        ";

        DB::statement($viewSql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_rekap_konten");
        Schema::dropIfExists('konten_youtube_live');
        Schema::dropIfExists('konten_youtube_shorts');
        Schema::dropIfExists('konten_youtube_video');
        Schema::dropIfExists('konten_tiktok');
        Schema::dropIfExists('konten_instagram');
        Schema::dropIfExists('konten_facebook');
        Schema::dropIfExists('kategori_konten');
        Schema::dropIfExists('platform');
    }
};
