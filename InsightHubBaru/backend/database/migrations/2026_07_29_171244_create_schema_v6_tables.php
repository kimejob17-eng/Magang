<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. platform
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

        // 2. kategori_konten
        if (!Schema::hasTable('kategori_konten')) {
            Schema::create('kategori_konten', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100);
                $table->foreignId('platform_id')->constrained('platform')->cascadeOnDelete();
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // 3. konten_facebook
        if (!Schema::hasTable('konten_facebook')) {
            Schema::create('konten_facebook', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')->constrained('kategori_konten')->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('jenis_konten', 50); // reels, post, dsb
                $table->string('tautan', 500);
                $table->date('tanggal_tayang');
                $table->integer('jangkauan')->default(0);
                $table->integer('total_interaksi')->default(0);
                $table->integer('suka')->default(0);
                $table->integer('komentar')->default(0);
                $table->integer('dibagikan')->default(0);
                $table->string('diinput_oleh', 150);
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // 4. konten_instagram
        if (!Schema::hasTable('konten_instagram')) {
            Schema::create('konten_instagram', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')->constrained('kategori_konten')->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('jenis_konten', 50); // reels, feeds, story
                $table->string('tautan', 500);
                $table->date('tanggal_tayang');
                $table->integer('jangkauan')->default(0);
                $table->integer('total_interaksi')->default(0);
                $table->integer('suka')->default(0);
                $table->integer('komentar')->default(0);
                $table->integer('dibagikan')->default(0);
                $table->string('diinput_oleh', 150);
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // 5. konten_tiktok
        if (!Schema::hasTable('konten_tiktok')) {
            Schema::create('konten_tiktok', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')->constrained('kategori_konten')->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('jenis_konten', 50);
                $table->string('tautan', 500);
                $table->date('tanggal_tayang');
                $table->integer('tayangan')->default(0); // tiktok pake tayangan
                $table->integer('total_interaksi')->default(0);
                $table->integer('suka')->default(0);
                $table->integer('komentar')->default(0);
                $table->integer('dibagikan')->default(0);
                $table->string('diinput_oleh', 150);
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // 6. konten_youtube_live
        if (!Schema::hasTable('konten_youtube_live')) {
            Schema::create('konten_youtube_live', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')->constrained('kategori_konten')->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('tautan', 500);
                $table->date('tanggal_tayang');
                $table->integer('jumlah_penayangan')->default(0);
                $table->integer('penambahan_subscriber')->default(0);
                $table->integer('penonton_puncak')->default(0);
                $table->integer('suka')->default(0);
                $table->integer('komentar')->default(0);
                $table->integer('dibagikan')->default(0);
                $table->string('diinput_oleh', 150);
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // 7. konten_youtube_video
        if (!Schema::hasTable('konten_youtube_video')) {
            Schema::create('konten_youtube_video', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')->constrained('kategori_konten')->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('tautan', 500);
                $table->date('tanggal_tayang');
                $table->integer('jumlah_penayangan')->default(0);
                $table->integer('penambahan_subscriber')->default(0);
                $table->integer('suka')->default(0);
                $table->integer('komentar')->default(0);
                $table->integer('dibagikan')->default(0);
                $table->string('diinput_oleh', 150);
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // 8. konten_youtube_shorts
        if (!Schema::hasTable('konten_youtube_shorts')) {
            Schema::create('konten_youtube_shorts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kategori_id')->constrained('kategori_konten')->cascadeOnDelete();
                $table->string('judul', 255);
                $table->string('tautan', 500);
                $table->date('tanggal_tayang');
                $table->integer('jumlah_penayangan')->default(0);
                $table->integer('penambahan_subscriber')->default(0);
                $table->integer('suka')->default(0);
                $table->integer('komentar')->default(0);
                $table->integer('dibagikan')->default(0);
                $table->string('diinput_oleh', 150);
                $table->timestamp('dibuat_pada')->useCurrent();
                $table->timestamp('diperbarui_pada')->useCurrent();
            });
        }

        // 9. View Rekap Konten
        // View ini menyatukan semua platform ke bentuk seragam untuk Dashboard
        $viewSql = "
            CREATE OR REPLACE VIEW v_rekap_konten AS
            -- FACEBOOK
            SELECT 
                CAST(CONCAT('FB-', kf.id) AS CHAR(50)) AS id_konten,
                p.slug AS platform_slug,
                p.nama AS platform_nama,
                kk.nama AS kategori,
                kf.judul,
                kf.jenis_konten,
                kf.tautan,
                kf.tanggal_tayang AS tgl_upload,
                kf.jangkauan AS reach,
                kf.total_interaksi AS total_interaksi,
                kf.suka, kf.komentar, kf.dibagikan,
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
                CAST(CONCAT('IG-', ki.id) AS CHAR(50)),
                p.slug, p.nama, kk.nama, ki.judul, ki.jenis_konten, ki.tautan, ki.tanggal_tayang,
                ki.jangkauan, ki.total_interaksi, ki.suka, ki.komentar, ki.dibagikan,
                0, 0, ki.diinput_oleh, ki.dibuat_pada
            FROM konten_instagram ki
            JOIN kategori_konten kk ON ki.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- TIKTOK
            SELECT 
                CAST(CONCAT('TK-', kt.id) AS CHAR(50)),
                p.slug, p.nama, kk.nama, kt.judul, kt.jenis_konten, kt.tautan, kt.tanggal_tayang,
                kt.tayangan, kt.total_interaksi, kt.suka, kt.komentar, kt.dibagikan,
                0, 0, kt.diinput_oleh, kt.dibuat_pada
            FROM konten_tiktok kt
            JOIN kategori_konten kk ON kt.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- YOUTUBE VIDEO
            SELECT 
                CAST(CONCAT('YV-', yv.id) AS CHAR(50)),
                p.slug, p.nama, kk.nama, yv.judul, 'Video', yv.tautan, yv.tanggal_tayang,
                yv.jumlah_penayangan, 0, yv.suka, yv.komentar, yv.dibagikan,
                yv.penambahan_subscriber, 0, yv.diinput_oleh, yv.dibuat_pada
            FROM konten_youtube_video yv
            JOIN kategori_konten kk ON yv.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- YOUTUBE SHORTS
            SELECT 
                CAST(CONCAT('YS-', ys.id) AS CHAR(50)),
                p.slug, p.nama, kk.nama, ys.judul, 'Shorts', ys.tautan, ys.tanggal_tayang,
                ys.jumlah_penayangan, 0, ys.suka, ys.komentar, ys.dibagikan,
                ys.penambahan_subscriber, 0, ys.diinput_oleh, ys.dibuat_pada
            FROM konten_youtube_shorts ys
            JOIN kategori_konten kk ON ys.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
            UNION ALL
            -- YOUTUBE LIVE
            SELECT 
                CAST(CONCAT('YL-', yl.id) AS CHAR(50)),
                p.slug, p.nama, kk.nama, yl.judul, 'Live', yl.tautan, yl.tanggal_tayang,
                yl.jumlah_penayangan, 0, yl.suka, yl.komentar, yl.dibagikan,
                yl.penambahan_subscriber, yl.penonton_puncak, yl.diinput_oleh, yl.dibuat_pada
            FROM konten_youtube_live yl
            JOIN kategori_konten kk ON yl.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
        ";

        DB::statement($viewSql);
    }

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
