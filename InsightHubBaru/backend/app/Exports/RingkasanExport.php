<?php

namespace App\Exports;

use App\Models\Konten\RekapKonten;
use Illuminate\Http\Request;

class RingkasanExport
{
    protected $request;

    /**
     * @param Request $request  Request berisi filters (platform, periode) dan
     *                          opsional chart_big, chart_small, chart_pie (Base64)
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getViewData()
    {
        $ringkasanPlatform = $this->request->get('ringkasan_platform', 'Semua Platform');
        $ringkasanPeriodeType = $this->request->get('ringkasan_periode_type', 'semua');
        $ringkasanBulan = $this->request->get('ringkasan_bulan', date('m'));
        $ringkasanTahun = $this->request->get('ringkasan_tahun', date('Y'));

        $metricsRingkasanQuery = RekapKonten::query();

        // 1. Filter Platform
        if ($ringkasanPlatform !== 'Semua Platform') {
            if ($ringkasanPlatform === 'YouTube') {
                $metricsRingkasanQuery->whereIn('sumber_tabel', ['youtube_video', 'youtube_shorts', 'youtube_live']);
            } else {
                $metricsRingkasanQuery->where('platform', ucfirst(strtolower($ringkasanPlatform)));
            }
        }

        // 2. Filter Periode
        $anchorDate = clone ($metricsRingkasanQuery->max('tgl_upload') ? \Carbon\Carbon::parse($metricsRingkasanQuery->max('tgl_upload')) : now());

        if ($ringkasanPeriodeType === 'bulanan' && $ringkasanTahun && $ringkasanBulan) {
            $date = \Carbon\Carbon::createFromDate($ringkasanTahun, $ringkasanBulan, 1);
            $metricsRingkasanQuery->whereBetween('tgl_upload', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()]);
        } elseif ($ringkasanPeriodeType === 'tahunan' && $ringkasanTahun) {
            $date = \Carbon\Carbon::createFromDate($ringkasanTahun, 1, 1);
            $metricsRingkasanQuery->whereBetween('tgl_upload', [$date->copy()->startOfYear(), $date->copy()->endOfYear()]);
        }

        $metricsRingkasan = $metricsRingkasanQuery->orderBy('tgl_upload', 'desc')->orderBy('id_konten', 'desc')->get();

        // 3. Agregasi Global
        $totalFollowersAsli = $metricsRingkasan->sum('followers_plus');
        // Gunakan logika persis yang ada di jsPlatformData (ringkasan.blade.php) 
        // yaitu (int)($totalFollowers * 0.012)
        $totalFollowers = (int)($totalFollowersAsli * 0.012);

        $totalReach      = $metricsRingkasan->sum('reach');
        $totalEngagement = $metricsRingkasan->sum('likes')
            + $metricsRingkasan->sum('comments')
            + $metricsRingkasan->sum('shares')
            + $metricsRingkasan->where('sumber_tabel', 'youtube_shorts')->sum('repost');
        $totalKonten     = $metricsRingkasan->count();
        $engagementRate  = $totalReach > 0 ? ($totalEngagement / $totalReach) * 100 : 0;

        $followerAccounts = [
            'Semua Platform' => 64800,
            'Instagram'      => 40308,
            'TikTok'         => 2217,
            'YouTube'        => 16900,
            'Facebook'       => 5375,
        ];
        $totalAkunFollowers = $followerAccounts[$ringkasanPlatform] ?? $followerAccounts['Semua Platform'];

        // 4. Agregasi Per Platform (untuk grafik SVG server-side)
        //    Konsisten dengan $jsPlatformData di ringkasan.blade.php
        $ig       = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'instagram');
        $tk       = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'tiktok');
        $fb       = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'facebook');
        $yt       = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'youtube');
        $ytVideo  = $metricsRingkasan->filter(fn($m) => strtolower($m->sumber_tabel) === 'youtube_video');
        $ytShorts = $metricsRingkasan->filter(fn($m) => strtolower($m->sumber_tabel) === 'youtube_shorts');
        $ytLive   = $metricsRingkasan->filter(fn($m) => strtolower($m->sumber_tabel) === 'youtube_live');

        $calcEng = function ($col) {
            return $col->sum('likes') + $col->sum('comments') + $col->sum('shares')
                 + $col->where('sumber_tabel', 'youtube_shorts')->sum('repost');
        };

        // Platform stats — sama dengan struktur $jsPlatformData di blade
        $platformStats = [
            'Instagram' => [
                'label'      => 'Instagram',
                'color'      => '#e1306c',
                'konten'     => $ig->count(),
                'reach'      => $ig->sum('reach'),
                'engagement' => $calcEng($ig),
                'likes'      => $ig->sum('likes'),
                'comments'   => $ig->sum('comments'),
                'shares'     => $ig->sum('shares'),
                'saves'      => $ig->sum('save') ?: $ig->sum('saves'),
                'repost'     => $ig->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
            ],
            'TikTok' => [
                'label'      => 'TikTok',
                'color'      => '#010101',
                'konten'     => $tk->count(),
                'reach'      => $tk->sum('reach'),
                'engagement' => $calcEng($tk),
                'likes'      => $tk->sum('likes'),
                'comments'   => $tk->sum('comments'),
                'shares'     => $tk->sum('shares'),
                'saves'      => $tk->sum('save') ?: $tk->sum('saves'),
                'repost'     => $tk->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
            ],
            'Facebook' => [
                'label'      => 'Facebook',
                'color'      => '#1877f2',
                'konten'     => $fb->count(),
                'reach'      => $fb->sum('reach'),
                'engagement' => $calcEng($fb),
                'likes'      => $fb->sum('likes'),
                'comments'   => $fb->sum('comments'),
                'shares'     => $fb->sum('shares'),
                'saves'      => $fb->sum('save') ?: $fb->sum('saves'),
                'repost'     => $fb->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
            ],
            'YouTube' => [
                'label'      => 'YouTube',
                'color'      => '#ff0000',
                'konten'     => $yt->count(),
                'reach'      => $yt->sum('reach'),
                'engagement' => $calcEng($yt),
                'likes'      => $yt->sum('likes'),
                'comments'   => $yt->sum('comments'),
                'shares'     => $yt->sum('shares'),
                'saves'      => $yt->sum('save') ?: $yt->sum('saves'),
                'repost'     => $yt->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
            ],
        ];

        // Sub-jenis YouTube (untuk mode platform = YouTube)
        $youtubeSubStats = [
            'Video' => [
                'label'      => 'Video',
                'color'      => '#ff0000',
                'konten'     => $ytVideo->count(),
                'reach'      => $ytVideo->sum('reach'),
                'engagement' => $calcEng($ytVideo),
                'likes'      => $ytVideo->sum('likes'),
                'comments'   => $ytVideo->sum('comments'),
                'shares'     => $ytVideo->sum('shares'),
                'saves'      => $ytVideo->sum('save') ?: $ytVideo->sum('saves'),
                'repost'     => $ytVideo->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
            ],
            'Shorts' => [
                'label'      => 'Shorts',
                'color'      => '#cc0000',
                'konten'     => $ytShorts->count(),
                'reach'      => $ytShorts->sum('reach'),
                'engagement' => $calcEng($ytShorts),
                'likes'      => $ytShorts->sum('likes'),
                'comments'   => $ytShorts->sum('comments'),
                'shares'     => $ytShorts->sum('shares'),
                'saves'      => $ytShorts->sum('save') ?: $ytShorts->sum('saves'),
                'repost'     => $ytShorts->sum('repost'),
            ],
            'Live' => [
                'label'      => 'Live',
                'color'      => '#ff6666',
                'konten'     => $ytLive->count(),
                'reach'      => $ytLive->sum('reach'),
                'engagement' => $calcEng($ytLive),
                'likes'      => $ytLive->sum('likes'),
                'comments'   => $ytLive->sum('comments'),
                'shares'     => $ytLive->sum('shares'),
                'saves'      => $ytLive->sum('save') ?: $ytLive->sum('saves'),
                'repost'     => $ytLive->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
            ],
        ];

        // Distribusi jenis konten untuk single platform (pie chart)
        $singlePlatformDistrib = [];
        if ($ringkasanPlatform !== 'Semua Platform' && $ringkasanPlatform !== 'YouTube') {
            $col = match(strtolower($ringkasanPlatform)) {
                'instagram' => $ig,
                'tiktok'    => $tk,
                'facebook'  => $fb,
                default     => collect(),
            };
            foreach ($col->groupBy('jenis') as $jenis => $jItems) {
                $singlePlatformDistrib[$jenis ?: 'Lainnya'] = $jItems->count();
            }
        }

        // 5. Grafik — ambil Base64 yang sudah tersimpan di filters/request
        // Snapshot diambil saat User menekan Export, bukan saat download
        $chartBig   = $this->request->get('chart_big')   ?? null;
        $chartSmall = $this->request->get('chart_small') ?? null;
        $chartPie   = $this->request->get('chart_pie')   ?? null;

        // Validasi ulang di sisi server sebelum pass ke view
        $validPattern = '/^data:image\/(png|jpeg|webp);base64,[A-Za-z0-9+\/=]+$/';
        if ($chartBig   && !preg_match($validPattern, $chartBig))   $chartBig   = null;
        if ($chartSmall && !preg_match($validPattern, $chartSmall)) $chartSmall = null;
        if ($chartPie   && !preg_match($validPattern, $chartPie))   $chartPie   = null;

        // 6. Logo — sama dengan export_pdf.blade.php (loogo.png, background #1e293b)
        $logoPath   = public_path('assets/loogo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $periodeLabel = 'Semua Waktu';
        if ($ringkasanPeriodeType === 'bulanan' && $ringkasanTahun && $ringkasanBulan) {
            $periodeLabel = \Carbon\Carbon::createFromDate($ringkasanTahun, $ringkasanBulan, 1)->translatedFormat('F Y');
        } elseif ($ringkasanPeriodeType === 'tahunan' && $ringkasanTahun) {
            $periodeLabel = 'Tahun ' . $ringkasanTahun;
        }

        return [
            'filterInfo' => [
                'platform' => $ringkasanPlatform,
                'periode'  => $periodeLabel,
            ],
            'agg' => [
                'total_konten'         => $totalKonten,
                'total_reach'          => $totalReach,
                'total_eng'            => $totalEngagement,
                'avg_er'               => $engagementRate,
                'followers_plus'       => $totalFollowers,
                'total_akun_followers' => $totalAkunFollowers,
            ],
            'charts' => [
                'big'   => $chartBig,
                'small' => $chartSmall,
                'pie'   => $chartPie,
            ],
            // Data per platform untuk grafik SVG server-side
            'platformStats'       => $platformStats,
            'youtubeSubStats'     => $youtubeSubStats,
            'singlePlatformDistrib' => $singlePlatformDistrib,
            'logoBase64'          => $logoBase64,
        ];
    }
}
