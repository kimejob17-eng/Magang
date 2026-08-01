<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konten\RekapKonten;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RingkasanController extends Controller
{
    // Nama platform di kolom `platform` VIEW v_rekap_konten -> slug & warna identitas
    // (persis sesuai Bagian 2.3 dokumentasi Ringkasan)
    private const PLATFORM_META = [
        'instagram' => ['label' => 'Instagram', 'slug' => 'instagram', 'warna' => '#e1306c'],
        'tiktok'    => ['label' => 'TikTok',    'slug' => 'tiktok',    'warna' => '#000000'],
        'facebook'  => ['label' => 'Facebook',  'slug' => 'facebook',  'warna' => '#1877f2'],
        'youtube'   => ['label' => 'YouTube',   'slug' => 'youtube',   'warna' => '#ff0000'],
    ];

    /**
     * GET /api/dashboard/ringkasan
     * KPI global + ringkasan per platform + 5 konten terbaru, sekaligus.
     */
    public function index(Request $request): JsonResponse
    {
        // Langkah 1 — Ambil semua data dari VIEW (tanpa filter, sesuai ringkasan.blade.php)
        $metrics = RekapKonten::orderBy('tgl_upload', 'desc')->get();

        // Langkah 2 — Hitung KPI Global
        $totalKonten     = $metrics->count();
        $totalFollowers  = $metrics->sum('followers_plus');
        $totalEngagement = $metrics->sum('likes') + $metrics->sum('comments') + $metrics->sum('shares');
        $totalReach      = $metrics->sum('reach');
        $engagementRate  = $totalReach > 0 ? round(($totalEngagement / $totalReach) * 100, 2) : 0;
        $pertumbuhanFollowers = (int) ($totalFollowers * 0.012);

        $kpiGlobal = [
            'total_konten'          => $totalKonten,
            'total_followers'       => $totalFollowers,
            'total_engagement'      => $totalEngagement,
            'total_reach'           => $totalReach,
            'engagement_rate'       => $engagementRate,
            'pertumbuhan_followers' => $pertumbuhanFollowers,
        ];

        // Langkah 3 — Kelompokkan data per platform (kolom `platform`, bukan `sumber_tabel`,
        // supaya 3 varian YouTube otomatis tergabung jadi satu — sesuai Bagian 2.3 & 6.3)
        $perPlatform = [];
        foreach (self::PLATFORM_META as $key => $meta) {
            $items = $metrics->filter(fn ($m) => strtolower($m->platform) === $key);

            $perPlatform[] = [
                'platform'         => $meta['label'],
                'slug'             => $meta['slug'],
                'warna'            => $meta['warna'],
                'total_konten'     => $items->count(),
                'total_reach'      => $items->sum('reach'),
                'total_engagement' => $items->sum('likes') + $items->sum('comments') + $items->sum('shares'),
                'total_followers'  => $items->sum('followers_plus'),
            ];
        }

        // Langkah 4 — Ambil 5 konten terbaru ($metrics sudah terurut desc)
        $kontenTerbaru = $metrics->take(5)->map(fn ($m) => $this->formatKonten($m))->values();

        return response()->json([
            'success' => true,
            'message' => 'Data ringkasan berhasil diambil',
            'data'    => [
                'kpi_global'     => $kpiGlobal,
                'per_platform'   => $perPlatform,
                'konten_terbaru' => $kontenTerbaru,
            ],
        ], 200);
    }

    /**
     * GET /api/dashboard/ringkasan/konten-terbaru
     * Daftar konten terbaru saja, jumlah bisa dikonfigurasi lewat ?limit= (default 5, max 50).
     */
    public function kontenTerbaru(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', 5);
        $limit = max(1, min(50, $limit)); // clamp ke rentang [1, 50]

        $konten = RekapKonten::orderBy('tgl_upload', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn ($m) => $this->formatKonten($m))
            ->values();

        return response()->json([
            'success'           => true,
            'message'           => 'Data konten terbaru berhasil diambil',
            'limit'             => $limit,
            'total_ditampilkan' => $konten->count(),
            'data'              => $konten,
        ], 200);
    }

    /**
     * Format satu baris RekapKonten menjadi array sesuai skema JSON
     * di Bagian 8.2 dokumentasi (field konten_terbaru[i]).
     */
    private function formatKonten(RekapKonten $m): array
    {
        return [
            'id_konten'            => $m->id_konten,
            'platform'             => $m->platform,
            'sumber_tabel'         => $m->sumber_tabel,
            'kategori'             => $m->kategori,
            'judul_konten'         => $m->judul_konten ?: ('Konten ' . ucfirst($m->platform)),
            'jenis'                => $m->jenis,
            'tautan'               => $m->tautan,
            'tgl_upload'           => $m->tgl_upload ? $m->tgl_upload->format('Y-m-d') : null,
            'tgl_upload_formatted' => $m->tgl_upload ? $m->tgl_upload->translatedFormat('d M Y') : null,
            'reach'                => $m->reach,
            'likes'                => $m->likes,
            'comments'             => $m->comments,
            'shares'               => $m->shares,
            'followers_plus'       => $m->followers_plus,
            'penonton_puncak'      => $m->penonton_puncak,
            'diinput_oleh'         => $m->diinput_oleh,
        ];
    }
}