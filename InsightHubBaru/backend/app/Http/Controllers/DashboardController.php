<?php

namespace App\Http\Controllers;

use App\Models\Konten\KategoriKonten;
use App\Models\Konten\KontenFacebook;
use App\Models\Konten\KontenInstagram;
use App\Models\Konten\KontenTiktok;
use App\Models\Konten\KontenYoutubeLive;
use App\Models\Konten\KontenYoutubeShorts;
use App\Models\Konten\KontenYoutubeVideo;
use App\Models\Pengaturan\Platform;
use App\Models\Konten\RekapKonten;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Form platform (slug) -> Eloquent model tabel konten yang sesuai
    private const PLATFORM_MODELS = [
        'facebook'  => KontenFacebook::class,
        'instagram' => KontenInstagram::class,
        'tiktok'    => KontenTiktok::class,
        'yt-live'   => KontenYoutubeLive::class,
        'yt-video'  => KontenYoutubeVideo::class,
        'yt-shorts' => KontenYoutubeShorts::class,
    ];

    // Form platform (slug) -> slug di tabel `platform` (3 varian YouTube -> 1 baris platform)
    private const PLATFORM_SLUG = [
        'facebook'  => 'facebook',
        'instagram' => 'instagram',
        'tiktok'    => 'tiktok',
        'yt-live'   => 'youtube',
        'yt-video'  => 'youtube',
        'yt-shorts' => 'youtube',
    ];

    // Value dropdown filter platform (persis sesuai <option> di laporan_blade.php:
    // instagram/tiktok/facebook/yt-live/yt-video/yt-shorts) -> kolom `sumber_tabel`
    // di VIEW v_rekap_konten, supaya YouTube Live/Video/Shorts bisa difilter terpisah.
    private const FILTER_PLATFORM_SUMBER = [
        'facebook'  => 'facebook',
        'instagram' => 'instagram',
        'tiktok'    => 'tiktok',
        'yt-live'   => 'youtube_live',
        'yt-video'  => 'youtube_video',
        'yt-shorts' => 'youtube_shorts',
    ];

    // Kolom sort yang boleh dipakai dari request (whitelist, cegah SQL injection lewat orderBy).
    // Persis nama kolom asli VIEW v_rekap_konten (dipakai jg oleh buildSortUrl() di laporan_blade.php).
    private const SORTABLE_COLUMNS = [
        'tgl_upload', 'platform', 'jenis', 'kategori', 'judul_konten',
        'reach', 'likes', 'comments', 'shares', 'followers_plus',
    ];

    public function index(Request $request)
    {
        // $metrics dipakai bareng di tab Ringkasan (recent list) DAN tab Input
        // (tabel "Riwayat Data" per-platform) -> tidak dibatasi limit supaya
        // riwayat per-platform tidak kepotong oleh konten platform lain.
        $metrics = RekapKonten::orderBy('tgl_upload', 'desc')->get();

        // Master kategori (untuk dropdown / referensi di halaman)
        $kategoris = KategoriKonten::with('platform')->get();

        // ---- Analitik Filters ----
        $filterPlatform     = $request->get('analitik_platform', 'all');
        $filterPeriodeType  = $request->get('analitik_periode_type', '');
        $filterDateStart    = $request->get('analitik_date_start', '');
        $filterDateEnd      = $request->get('analitik_date_end', '');
        $filterBulan        = $request->get('analitik_bulan', '');
        $filterTahunBulan   = $request->get('analitik_tahun_bulan', '');
        $filterTahun        = $request->get('analitik_tahun', '');

        $metricsAnalitik = RekapKonten::query();
        $this->applyPlatformFilter($metricsAnalitik, $filterPlatform);
        $this->applyPeriodeFilter(
            $metricsAnalitik,
            $filterPeriodeType,
            $filterDateStart,
            $filterDateEnd,
            $filterBulan,
            $filterTahunBulan,
            $filterTahun
        );

        $metricsAnalitik = $metricsAnalitik->orderBy('tgl_upload', 'desc')->get();

        $analitikFilters = [
            'platform'     => $filterPlatform,
            'periode_type' => $filterPeriodeType,
            'date_start'   => $filterDateStart,
            'date_end'     => $filterDateEnd,
            'bulan'        => $filterBulan,
            'tahun_bulan'  => $filterTahunBulan,
            'tahun'        => $filterTahun,
        ];

        // ---- Laporan Filters ----
        $lapPlatform    = $request->get('lap_platform', 'all');
        $lapPeriodeType = $request->get('lap_periode_type', '');
        $lapDateStart   = $request->get('lap_date_start', '');
        $lapDateEnd     = $request->get('lap_date_end', '');
        $lapBulan       = $request->get('lap_bulan', '');
        $lapTahunBulan  = $request->get('lap_tahun_bulan', '');
        $lapTahun       = $request->get('lap_tahun', '');

        $lapSearch = $request->get('lap_search', '');
        $lapSort   = $request->get('lap_sort', 'tgl_upload');
        $lapDir    = $request->get('lap_dir', 'desc');

        // Whitelist kolom sort supaya tidak error / rawan injeksi kalau value dari form beda
        if (!in_array($lapSort, self::SORTABLE_COLUMNS, true)) {
            $lapSort = 'tgl_upload';
        }
        $lapDir = strtolower($lapDir) === 'asc' ? 'asc' : 'desc';

        $metricsLaporanQuery = RekapKonten::query();
        $this->applyPlatformFilter($metricsLaporanQuery, $lapPlatform);
        $this->applyPeriodeFilter(
            $metricsLaporanQuery,
            $lapPeriodeType,
            $lapDateStart,
            $lapDateEnd,
            $lapBulan,
            $lapTahunBulan,
            $lapTahun
        );

        if ($lapSearch) {
            $metricsLaporanQuery->where(function ($q) use ($lapSearch) {
                $q->where('judul_konten', 'like', "%{$lapSearch}%")
                  ->orWhere('kategori', 'like', "%{$lapSearch}%")
                  ->orWhere('platform', 'like', "%{$lapSearch}%");
            });
        }

        $metricsLaporanQuery->orderBy($lapSort, $lapDir);

        $metricsLaporan = $metricsLaporanQuery->paginate(10)->appends($request->all());

        // Agregat dihitung ulang dari query yg sama (tanpa order/paginate)
        $aggQuery = RekapKonten::query();
        $this->applyPlatformFilter($aggQuery, $lapPlatform);
        $this->applyPeriodeFilter($aggQuery, $lapPeriodeType, $lapDateStart, $lapDateEnd, $lapBulan, $lapTahunBulan, $lapTahun);
        if ($lapSearch) {
            $aggQuery->where(function ($q) use ($lapSearch) {
                $q->where('judul_konten', 'like', "%{$lapSearch}%")
                  ->orWhere('kategori', 'like', "%{$lapSearch}%")
                  ->orWhere('platform', 'like', "%{$lapSearch}%");
            });
        }

        $laporanAgg = [
            'total_konten' => (clone $aggQuery)->count(),
            'total_reach'  => (clone $aggQuery)->sum('reach'),
            'total_eng'    => (clone $aggQuery)->sum('likes')
                             + (clone $aggQuery)->sum('comments')
                             + (clone $aggQuery)->sum('shares'),
        ];
        $laporanAgg['avg_er'] = $laporanAgg['total_reach'] > 0
            ? ($laporanAgg['total_eng'] / $laporanAgg['total_reach']) * 100
            : 0;

        $lapFilters = [
            'platform'     => $lapPlatform,
            'periode_type' => $lapPeriodeType,
            'date_start'   => $lapDateStart,
            'date_end'     => $lapDateEnd,
            'bulan'        => $lapBulan,
            'tahun_bulan'  => $lapTahunBulan,
            'tahun'        => $lapTahun,
            'search'       => $lapSearch,
            'sort'         => $lapSort,
            'dir'          => $lapDir,
        ];

        return view('pages.Dashboard.index', compact(
            'metrics', 'kategoris', 'metricsAnalitik', 'analitikFilters',
            'metricsLaporan', 'laporanAgg', 'lapFilters'
        ));
    }

    /**
     * Filter platform pada query v_rekap_konten.
     * Value dropdown (facebook/instagram/tiktok/yt-live/yt-video/yt-shorts)
     * dipetakan ke kolom `sumber_tabel`, supaya 3 varian YouTube bisa
     * difilter terpisah persis sesuai <option> di laporan_blade.php.
     * Kalau suatu saat ada filter 'youtube' (gabungan semua varian),
     * fallback ke kolom `platform` = 'youtube'.
     */
    private function applyPlatformFilter($query, ?string $filterPlatform): void
    {
        if (!$filterPlatform || $filterPlatform === 'all') {
            return;
        }

        if (isset(self::FILTER_PLATFORM_SUMBER[$filterPlatform])) {
            $query->where('sumber_tabel', self::FILTER_PLATFORM_SUMBER[$filterPlatform]);
        } else {
            // Fallback ini kepakai oleh dropdown Analitik (analitik.blade.php), yang cuma
            // punya 1 opsi generik "youtube" (beda dgn dropdown Laporan yg 6 opsi terpisah).
            // Kolom `platform` di VIEW v_rekap_konten nilainya kapital ('Facebook','Youtube',
            // dst), jadi disamakan dulu formatnya.
            $query->where('platform', ucfirst(strtolower($filterPlatform)));
        }
    }

    private function applyPeriodeFilter(
        $query,
        ?string $periodeType,
        ?string $dateStart,
        ?string $dateEnd,
        ?string $bulan,
        ?string $tahunBulan,
        ?string $tahun
    ): void {
        if ($periodeType === 'range' && $dateStart && $dateEnd) {
            $query->whereBetween('tgl_upload', [$dateStart, $dateEnd]);
        } elseif ($periodeType === 'bulan' && $bulan && $tahunBulan) {
            $query->whereYear('tgl_upload', $tahunBulan)
                  ->whereMonth('tgl_upload', $bulan);
        } elseif ($periodeType === 'tahun' && $tahun) {
            $query->whereYear('tgl_upload', $tahun);
        }
    }

    public function storeMetric(Request $request)
    {
        $validated = $request->validate([
            'platform'     => 'required|string|in:' . implode(',', array_keys(self::PLATFORM_MODELS)),
            'category'     => 'nullable|string',
            'title'        => 'nullable|string',
            'publish_date' => 'nullable|date',
            'content_type' => 'nullable|string',
            'url'          => 'nullable|url',
            'views'        => 'nullable|numeric',
            'interactions' => 'nullable|numeric',
            'like'         => 'nullable|numeric',
            'comment'      => 'nullable|numeric',
            'share'        => 'nullable|numeric',
            'subscribers'  => 'nullable|numeric',
            'peak_viewers' => 'nullable|numeric',
        ]);

        $platformKey = $validated['platform'];
        $modelClass  = self::PLATFORM_MODELS[$platformKey];

        // Cari / buat kategori sesuai platform induk (facebook/instagram/tiktok/youtube)
        $kategoriId = null;
        if (!empty($validated['category'])) {
            $platformRow = Platform::where('slug', self::PLATFORM_SLUG[$platformKey])->first();

            if ($platformRow) {
                $kategori = KategoriKonten::firstOrCreate([
                    'nama'        => $validated['category'],
                    'platform_id' => $platformRow->id,
                ]);
                $kategoriId = $kategori->id;
            }
        }

        $data = [
            'kategori_id'    => $kategoriId,
            'judul'          => $validated['title'] ?? '(Tanpa Judul)',
            'tautan'         => $validated['url'] ?? null,
            'tanggal_tayang' => $validated['publish_date'],
            'suka'           => $validated['like'] ?? 0,
            'komentar'       => $validated['comment'] ?? 0,
            'dibagikan'      => $validated['share'] ?? 0,
        ];

        // Nama kolom "views" & "jenis konten" beda-beda tiap tabel
        switch ($platformKey) {
            case 'facebook':
                $data['jenis_konten']    = $validated['content_type'] ?? 'Image Post';
                $data['tayangan']        = $validated['views'] ?? 0;
                $data['total_interaksi'] = $validated['interactions'] ?? 0;
                break;

            case 'instagram':
                $data['jenis_konten']    = $validated['content_type'] ?? 'Feed Post';
                $data['jangkauan']       = $validated['views'] ?? 0;
                $data['total_interaksi'] = $validated['interactions'] ?? 0;
                break;

            case 'tiktok':
                $data['jenis_konten']    = $validated['content_type'] ?? 'Short Video';
                $data['tayangan']        = $validated['views'] ?? 0;
                $data['total_interaksi'] = $validated['interactions'] ?? 0;
                break;

            case 'yt-live':
                $data['jumlah_penayangan']     = $validated['views'] ?? 0;
                $data['penambahan_subscriber'] = $validated['subscribers'] ?? 0;
                $data['penonton_puncak']       = $validated['peak_viewers'] ?? 0;
                break;

            case 'yt-video':
            case 'yt-shorts':
                $data['jumlah_penayangan']     = $validated['views'] ?? 0;
                $data['penambahan_subscriber'] = $validated['subscribers'] ?? 0;
                break;
        }

        $modelClass::create($data);

        return redirect()->route('dashboard')->with('success', ucfirst($platformKey) . ' metrics have been updated in the database.');
    }

    /**
     * Export platform-specific CSV template and data.
     */
    public function exportCsv(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|in:' . implode(',', array_keys(self::PLATFORM_MODELS)),
        ]);

        $platformKey = $request->query('platform');
        $modelClass  = self::PLATFORM_MODELS[$platformKey];

        // Fetch records with category relation
        $records = $modelClass::with('kategori')->orderBy('tanggal_tayang', 'desc')->get();

        // Define headers and data mapping based on platform
        $headers = [];
        $callback = null;

        switch ($platformKey) {
            case 'facebook':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Jenis Konten', 'Link Konten', 'Tayangan (Impressions)', 'Interaksi (Engagement)', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->jenis_konten,
                        $row->tautan,
                        $row->tayangan,
                        $row->total_interaksi,
                        $row->suka,
                        $row->komentar,
                        $row->dibagikan
                    ];
                };
                break;

            case 'instagram':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Jenis Konten', 'Link Konten', 'Jangkauan (Reach)', 'Interaksi (Engagement)', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->jenis_konten,
                        $row->tautan,
                        $row->jangkauan,
                        $row->total_interaksi,
                        $row->suka,
                        $row->komentar,
                        $row->dibagikan
                    ];
                };
                break;

            case 'tiktok':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Jenis Konten', 'Link Konten', 'Tayangan (Impressions)', 'Interaksi (Engagement)', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->jenis_konten,
                        $row->tautan,
                        $row->tayangan,
                        $row->total_interaksi,
                        $row->suka,
                        $row->komentar,
                        $row->dibagikan
                    ];
                };
                break;

            case 'yt-live':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Link Konten', 'Jumlah Penayangan (Views)', 'Penambahan Subscriber', 'Penonton Puncak (Peak Viewers)', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->tautan,
                        $row->jumlah_penayangan,
                        $row->penambahan_subscriber,
                        $row->penonton_puncak,
                        $row->suka,
                        $row->komentar,
                        $row->dibagikan
                    ];
                };
                break;

            case 'yt-video':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Link Konten', 'Jumlah Penayangan (Views)', 'Penambahan Subscriber', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->tautan,
                        $row->jumlah_penayangan,
                        $row->penambahan_subscriber,
                        $row->suka,
                        $row->komentar,
                        $row->dibagikan
                    ];
                };
                break;

            case 'yt-shorts':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Link Konten', 'Jumlah Penayangan (Views)', 'Penambahan Subscriber', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->tautan,
                        $row->jumlah_penayangan,
                        $row->penambahan_subscriber,
                        $row->suka,
                        $row->komentar,
                        $row->dibagikan
                    ];
                };
                break;
        }

        // Stream the CSV response
        $filename = 'Export_' . ucfirst($platformKey) . '_' . date('Ymd_His') . '.csv';

        $responseHeaders = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        return response()->stream(function() use ($headers, $records, $callback) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $headers);

            foreach ($records as $row) {
                fputcsv($file, $callback($row));
            }

            fclose($file);
        }, 200, $responseHeaders);
    }

    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanExport($request), 'Laporan_Performa_Konten_' . date('Ymd_His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $export = new \App\Exports\LaporanExport($request);
        $data = $export->getViewData();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.Report.export_pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Performa_Konten_' . date('Ymd_His') . '.pdf');
    }
}