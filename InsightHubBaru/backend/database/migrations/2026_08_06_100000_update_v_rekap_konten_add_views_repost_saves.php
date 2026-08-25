<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_rekap_konten AS

            /* =========================================================
               FACEBOOK
               ========================================================= */
            SELECT
                CAST(CONCAT('FB-', kf.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('facebook' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,

                kf.judul AS judul_konten,
                kf.jenis_konten AS jenis,
                kf.tautan,
                kf.tanggal_tayang AS tgl_upload,

                kf.views AS reach,

                (
                    COALESCE(kf.likes, 0)
                    + COALESCE(kf.comments, 0)
                    + COALESCE(kf.shares, 0)
                ) AS total_interaksi,

                kf.likes AS likes,
                kf.comments AS comments,
                kf.shares AS shares,

                0 AS followers_plus,
                0 AS penonton_puncak,

                kf.views AS views,
                0 AS repost,
                kf.saves AS saves,

                kf.diinput_oleh,
                kf.dibuat_pada

            FROM konten_facebook kf
            JOIN kategori_konten kk
                ON kf.kategori_id = kk.id
            JOIN platform p
                ON kk.platform_id = p.id


            UNION ALL


            /* =========================================================
               INSTAGRAM
               ========================================================= */
            SELECT
                CAST(CONCAT('IG-', ki.id) AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS id_konten,
                CAST('instagram' AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS sumber_tabel,
                CAST(p.nama AS CHAR(50)) COLLATE utf8mb4_unicode_ci AS platform,
                kk.nama AS kategori,

                ki.judul AS judul_konten,
                ki.jenis_konten AS jenis,
                ki.tautan,
                ki.tanggal_tayang AS tgl_upload,

                ki.reach AS reach,

                (
                    COALESCE(ki.likes, 0)
                    + COALESCE(ki.comments, 0)
                    + COALESCE(ki.shares, 0)
                    + COALESCE(ki.repost, 0)
                ) AS total_interaksi,

                ki.likes AS likes,
                ki.comments AS comments,
                ki.shares AS shares,

                0 AS followers_plus,
                0 AS penonton_puncak,

                ki.views AS views,
                ki.repost AS repost,
                0 AS saves,

                ki.diinput_oleh,
                ki.dibuat_pada

            FROM konten_instagram ki
            JOIN kategori_konten kk
                ON ki.kategori_id = kk.id
            JOIN platform p
                ON kk.platform_id = p.id


            UNION ALL


            /* =========================================================
               TIKTOK
               ========================================================= */
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

                kt.likes AS likes,
                kt.comments AS comments,
                kt.shares AS shares,

                0 AS followers_plus,
                0 AS penonton_puncak,

                kt.tayangan AS views,
                0 AS repost,
                kt.saves AS saves,

                kt.diinput_oleh,
                kt.dibuat_pada

            FROM konten_tiktok kt
            JOIN kategori_konten kk
                ON kt.kategori_id = kk.id
            JOIN platform p
                ON kk.platform_id = p.id


            UNION ALL


            /* =========================================================
               YOUTUBE VIDEO
               ========================================================= */
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

                (
                    COALESCE(yv.likes, 0)
                    + COALESCE(yv.comments, 0)
                    + COALESCE(yv.shares, 0)
                ) AS total_interaksi,

                yv.likes AS likes,
                yv.comments AS comments,
                yv.shares AS shares,

                yv.penambahan_subscriber AS followers_plus,
                0 AS penonton_puncak,

                yv.jumlah_penayangan AS views,
                0 AS repost,
                0 AS saves,

                yv.diinput_oleh,
                yv.dibuat_pada

            FROM konten_youtube_video yv
            JOIN kategori_konten kk
                ON yv.kategori_id = kk.id
            JOIN platform p
                ON kk.platform_id = p.id


            UNION ALL


            /* =========================================================
               YOUTUBE SHORTS
               ========================================================= */
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

                (
                    COALESCE(ys.likes, 0)
                    + COALESCE(ys.comments, 0)
                    + COALESCE(ys.repost, 0)
                ) AS total_interaksi,

                ys.likes AS likes,
                ys.comments AS comments,

                0 AS shares,

                ys.penambahan_subscriber AS followers_plus,
                0 AS penonton_puncak,

                ys.jumlah_penayangan AS views,
                ys.repost AS repost,
                0 AS saves,

                ys.diinput_oleh,
                ys.dibuat_pada

            FROM konten_youtube_shorts ys
            JOIN kategori_konten kk
                ON ys.kategori_id = kk.id
            JOIN platform p
                ON kk.platform_id = p.id


            UNION ALL


            /* =========================================================
               YOUTUBE LIVE
               ========================================================= */
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

                (
                    COALESCE(yl.likes, 0)
                    + COALESCE(yl.comments, 0)
                    + COALESCE(yl.shares, 0)
                ) AS total_interaksi,

                yl.likes AS likes,
                yl.comments AS comments,
                yl.shares AS shares,

                yl.penambahan_subscriber AS followers_plus,
                yl.penonton_puncak AS penonton_puncak,

                yl.jumlah_penayangan AS views,
                0 AS repost,
                0 AS saves,

                yl.diinput_oleh,
                yl.dibuat_pada

            FROM konten_youtube_live yl
            JOIN kategori_konten kk
                ON yl.kategori_id = kk.id
            JOIN platform p
                ON kk.platform_id = p.id
        ");
    }

    public function down(): void
    {
        DB::statement("
            DROP VIEW IF EXISTS v_rekap_konten
        ");
    }
};