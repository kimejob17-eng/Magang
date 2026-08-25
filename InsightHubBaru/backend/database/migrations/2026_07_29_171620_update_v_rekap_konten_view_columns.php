<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $viewSql = "
            CREATE OR REPLACE VIEW v_rekap_konten AS
            -- FACEBOOK
            SELECT 
                CAST(CONCAT('FB-', kf.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('facebook' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,
                kf.judul AS judul_konten,
                kf.jenis_konten AS jenis,
                kf.tautan,
                kf.tanggal_tayang AS tgl_upload,
                kf.jangkauan AS reach,
                kf.total_interaksi AS total_interaksi,
                kf.suka AS likes,
                kf.komentar AS comments,
                kf.dibagikan AS shares,
                0 AS followers_plus,
                0 AS penonton_puncak,
                kf.diinput_oleh,
                kf.dibuat_pada
            FROM konten_facebook kf
            JOIN kategori_konten kk ON kf.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id

            UNION ALL

            -- INSTAGRAM
            SELECT 
                CAST(CONCAT('IG-', ki.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('instagram' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,
                ki.judul AS judul_konten,
                ki.jenis_konten AS jenis,
                ki.tautan,
                ki.tanggal_tayang AS tgl_upload,
                ki.jangkauan AS reach,
                ki.total_interaksi AS total_interaksi,
                ki.suka AS likes,
                ki.komentar AS comments,
                ki.dibagikan AS shares,
                0 AS followers_plus,
                0 AS penonton_puncak,
                ki.diinput_oleh,
                ki.dibuat_pada
            FROM konten_instagram ki
            JOIN kategori_konten kk ON ki.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id

            UNION ALL

            -- TIKTOK
            SELECT 
                CAST(CONCAT('TK-', kt.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('tiktok' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,
                kt.judul AS judul_konten,
                kt.jenis_konten AS jenis,
                kt.tautan,
                kt.tanggal_tayang AS tgl_upload,
                kt.tayangan AS reach,
                kt.total_interaksi AS total_interaksi,
                kt.suka AS likes,
                kt.komentar AS comments,
                kt.dibagikan AS shares,
                0 AS followers_plus,
                0 AS penonton_puncak,
                kt.diinput_oleh,
                kt.dibuat_pada
            FROM konten_tiktok kt
            JOIN kategori_konten kk ON kt.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id

            UNION ALL

            -- YOUTUBE VIDEO
            SELECT 
                CAST(CONCAT('YV-', yv.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('youtube_video' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,
                yv.judul AS judul_konten,
                CAST('Video' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS jenis,
                yv.tautan,
                yv.tanggal_tayang AS tgl_upload,
                yv.jumlah_penayangan AS reach,
                (yv.suka + yv.komentar + yv.dibagikan) AS total_interaksi,
                yv.suka AS likes,
                yv.komentar AS comments,
                yv.dibagikan AS shares,
                yv.penambahan_subscriber AS followers_plus,
                0 AS penonton_puncak,
                yv.diinput_oleh,
                yv.dibuat_pada
            FROM konten_youtube_video yv
            JOIN kategori_konten kk ON yv.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id

            UNION ALL

            -- YOUTUBE SHORTS
            SELECT 
                CAST(CONCAT('YS-', ys.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('youtube_shorts' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,
                ys.judul AS judul_konten,
                CAST('Shorts' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS jenis,
                ys.tautan,
                ys.tanggal_tayang AS tgl_upload,
                ys.jumlah_penayangan AS reach,
                (ys.suka + ys.komentar + ys.dibagikan) AS total_interaksi,
                ys.suka AS likes,
                ys.komentar AS comments,
                ys.dibagikan AS shares,
                ys.penambahan_subscriber AS followers_plus,
                0 AS penonton_puncak,
                ys.diinput_oleh,
                ys.dibuat_pada
            FROM konten_youtube_shorts ys
            JOIN kategori_konten kk ON ys.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id

            UNION ALL

            -- YOUTUBE LIVE
            SELECT 
                CAST(CONCAT('YL-', yl.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('youtube_live' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,
                yl.judul AS judul_konten,
                CAST('Live' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS jenis,
                yl.tautan,
                yl.tanggal_tayang AS tgl_upload,
                yl.jumlah_penayangan AS reach,
                (yl.suka + yl.komentar + yl.dibagikan) AS total_interaksi,
                yl.suka AS likes,
                yl.komentar AS comments,
                yl.dibagikan AS shares,
                yl.penambahan_subscriber AS followers_plus,
                yl.penonton_puncak AS penonton_puncak,
                yl.diinput_oleh,
                yl.dibuat_pada
            FROM konten_youtube_live yl
            JOIN kategori_konten kk ON yl.kategori_id = kk.id
            JOIN platform p ON kk.platform_id = p.id
        ";

        DB::statement($viewSql);
    }

    public function down(): void
    {
        // Revert to simple/basic columns in case of rollback
        $viewSql = "
            CREATE OR REPLACE VIEW v_rekap_konten AS
            SELECT 
                CAST(CONCAT('FB-', kf.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
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
        ";
        DB::statement($viewSql);
    }
};

