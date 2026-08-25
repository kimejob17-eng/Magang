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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

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
        'reach', 'likes', 'comments', 'shares', 'views', 'repost', 'saves',
        'followers_plus',
    ];

    public function index(Request $request)
    {
        // Block 'user' role from accessing the input tab via URL
        if ($request->query('tab') === 'input' && auth()->check() && auth()->user()->role === 'user') {
            return redirect()->route('dashboard', ['tab' => 'ringkasan'])->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk mengakses Input Data.');
        }

        // $metrics dipakai bareng di tab Ringkasan (recent list) DAN tab Input
        // (tabel "Riwayat Data" per-platform) -> tidak dibatasi limit supaya
        // riwayat per-platform tidak kepotong oleh konten platform lain.
        $metrics = RekapKonten::orderBy('tgl_upload', 'desc')->get();

        // Master kategori (untuk dropdown / referensi di halaman)
        $kategoris = KategoriKonten::with('platform')->get();

        // ---- Ringkasan Filters ----
        $ringkasanPlatform = $request->get('ringkasan_platform', 'Semua Platform');
        $ringkasanPeriode = $request->get('ringkasan_periode', 'semua');
        $ringkasanBulan = $request->get('ringkasan_bulan', date('m'));
        $ringkasanTahun = $request->get('ringkasan_tahun', date('Y'));

        // Mengambil daftar tahun unik dari database untuk dropdown filter
        $dates = RekapKonten::selectRaw('YEAR(tgl_upload) as year')
            ->whereNotNull('tgl_upload')
            ->groupByRaw('YEAR(tgl_upload)')
            ->orderByRaw('YEAR(tgl_upload) DESC')
            ->get();
        
        $availableYears = [];
        foreach ($dates as $d) {
            $availableYears[] = $d->year;
        }

        $metricsRingkasanQuery = RekapKonten::query();

        // 1. Filter Platform
        if ($ringkasanPlatform !== 'Semua Platform') {
            if ($ringkasanPlatform === 'YouTube') {
                $metricsRingkasanQuery->whereIn('sumber_tabel', ['youtube_video', 'youtube_shorts', 'youtube_live']);
            } else {
                $metricsRingkasanQuery->where('platform', ucfirst(strtolower($ringkasanPlatform)));
            }
        }

        // 2. Filter Periode (berdasarkan input terpisah)
        $metricsRingkasanPrevQuery = clone $metricsRingkasanQuery;
        
        if ($ringkasanPeriode === 'bulanan' && $ringkasanTahun && $ringkasanBulan) {
            $date = \Carbon\Carbon::createFromDate($ringkasanTahun, $ringkasanBulan, 1);
            $metricsRingkasanQuery->whereBetween('tgl_upload', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()]);
            $metricsRingkasanPrevQuery->whereBetween('tgl_upload', [$date->copy()->subMonth()->startOfMonth(), $date->copy()->subMonth()->endOfMonth()]);
        } elseif ($ringkasanPeriode === 'tahunan' && $ringkasanTahun) {
            $date = \Carbon\Carbon::createFromDate($ringkasanTahun, 1, 1);
            $metricsRingkasanQuery->whereBetween('tgl_upload', [$date->copy()->startOfYear(), $date->copy()->endOfYear()]);
            $metricsRingkasanPrevQuery->whereBetween('tgl_upload', [$date->copy()->subYear()->startOfYear(), $date->copy()->subYear()->endOfYear()]);
        } else {
            // Untuk 'semua' waktu, periode sebelumnya kosong saja
            $metricsRingkasanPrevQuery->whereRaw('1 = 0');
        }

        $metricsRingkasan = $metricsRingkasanQuery->orderBy('tgl_upload', 'desc')->orderBy('id_konten', 'desc')->get();
        $metricsRingkasanPrev = $metricsRingkasanPrevQuery->orderBy('tgl_upload', 'desc')->get();

        // 3. Tren badge khusus mode 'semua' (Semua Waktu): karena tidak ada
        // "periode sebelumnya" yang logis untuk all-time, badge persen tetap
        // dihitung dari jendela rolling 30 hari terakhir vs 30 hari sebelum itu,
        // TANPA mengubah angka total KPI utama (yang tetap all-time).
        if ($ringkasanPeriode === 'semua') {
            // Badge untuk mode 'Semua Waktu' membandingkan SEPARUH data yang
            // lebih baru vs SEPARUH data yang lebih lama (dari seluruh riwayat
            // upload, diurutkan kronologis). Ini memberi tren pertumbuhan yang
            // natural: makin banyak & makin bagus performa konten belakangan
            // dibanding konten-konten sebelumnya, makin tinggi angkanya —
            // bukan cuma dibandingkan ke 1 konten pertama atau 1 konten
            // terbaru saja.
            $sortedAsc = $metricsRingkasan->sortBy([
                ['tgl_upload', 'asc'],
                ['id_konten', 'asc'],
            ])->values();

            $total = $sortedAsc->count();
            $half = intdiv($total, 2);

            $olderHalf = $sortedAsc->slice(0, $half);
            $newerHalf = $sortedAsc->slice($half);

            $metricsRingkasanBadgeCurr = $newerHalf;
            $metricsRingkasanBadgePrev = $olderHalf;
        } else {
            // Periode lain: badge tetap pakai current/prev yang sama seperti KPI utama
            $metricsRingkasanBadgeCurr = $metricsRingkasan;
            $metricsRingkasanBadgePrev = $metricsRingkasanPrev;
        }


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
                             + (clone $aggQuery)->sum('shares')
                             + (clone $aggQuery)->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
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

        $exportRequests = collect();
        if (auth()->check()) {
            if (in_array(auth()->user()->role, ['super-admin', 'admin'])) {
                $exportRequests = \App\Models\ExportRequest::with('user')->orderBy('created_at', 'desc')->get();
            } else {
                $exportRequests = \App\Models\ExportRequest::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
            }
        }

        return view('pages.Dashboard.index', compact(
            'metrics', 'kategoris', 'metricsAnalitik', 'analitikFilters',
            'metricsLaporan', 'laporanAgg', 'lapFilters',
            'metricsRingkasan', 'metricsRingkasanPrev', 'metricsRingkasanBadgeCurr', 'metricsRingkasanBadgePrev', 'ringkasanPlatform',
            'ringkasanPeriode', 'ringkasanBulan', 'ringkasanTahun', 'availableYears', 'exportRequests'
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
            'url'          => 'nullable|string',
            'reach'        => 'nullable|string',
            'views'        => 'nullable|string',
            'repost'       => 'nullable|string',
            'save'         => 'nullable|string',
            'interactions' => 'nullable|string',
            'like'         => 'nullable|string',
            'comment'      => 'nullable|string',
            'share'        => 'nullable|string',
            'subscribers'  => 'nullable|string',
            'peak_viewers' => 'nullable|string',
        ]);

        $platformKey = $validated['platform'];

        // 1. Authorization RBAC secara Dinamis
        $platformMap = [
            'facebook'  => 'input.facebook',
            'instagram' => 'input.instagram',
            'tiktok'    => 'input.tiktok',
            'yt-live'   => 'input.youtube-live',
            'yt-video'  => 'input.youtube-video',
            'yt-shorts' => 'input.youtube-shorts',
        ];

        $menuDetailSlug = $platformMap[$platformKey] ?? null;

        if (!$menuDetailSlug) {
            abort(403, 'Platform tidak valid.');
        }

        $user = $request->user();
        if (!$user || !$user->role_id) {
            abort(403, 'Unauthorized Access.');
        }

        $hasPermission = $user->roleModel->permissions()
            ->whereHas('menuDetail', function ($query) use ($menuDetailSlug) {
                $query->where('slug', $menuDetailSlug);
            })
            ->whereHas('permission', function ($query) {
                $query->where('slug', 'create');
            })
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Forbidden: Anda tidak memiliki izin untuk menginput data ' . ucfirst($platformKey));
        }

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
            'likes'          => $this->parseIndonesianMetric($validated['like'] ?? 0),
            'comments'       => $this->parseIndonesianMetric($validated['comment'] ?? 0),
            'shares'         => $this->parseIndonesianMetric($validated['share'] ?? 0),
        ];

        // Nama kolom "views" & "jenis konten" beda-beda tiap tabel
        switch ($platformKey) {
            case 'facebook':
                $data['jenis_konten'] = $validated['content_type'] ?? 'Image Post';
                $data['views']        = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['saves']        = $this->parseIndonesianMetric($validated['save'] ?? 0);
                break;

            case 'instagram':
                $data['jenis_konten'] = $validated['content_type'] ?? 'Feed Post';
                $data['reach']        = $this->parseIndonesianMetric($validated['reach'] ?? 0);
                $data['views']        = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['repost']       = $this->parseIndonesianMetric($validated['repost'] ?? 0);
                break;

            case 'tiktok':
                $data['jenis_konten']    = $validated['content_type'] ?? 'Short Video';
                $data['tayangan']        = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['total_interaksi'] = $this->parseIndonesianMetric($validated['interactions'] ?? 0);
                $data['saves']           = $this->parseIndonesianMetric($validated['save'] ?? 0);
                break;

            case 'yt-live':
                $data['jumlah_penayangan']     = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['penambahan_subscriber'] = $this->parseIndonesianMetric($validated['subscribers'] ?? 0);
                $data['penonton_puncak']       = $this->parseIndonesianMetric($validated['peak_viewers'] ?? 0);
                break;

            case 'yt-video':
                $data['jumlah_penayangan']     = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['penambahan_subscriber'] = $this->parseIndonesianMetric($validated['subscribers'] ?? 0);
                break;

            case 'yt-shorts':
                $data['jumlah_penayangan']     = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['penambahan_subscriber'] = $this->parseIndonesianMetric($validated['subscribers'] ?? 0);
                // Kolom fisik di konten_youtube_shorts sudah di-rename dari
                // 'shares' -> 'repost' (label form "Share" sebenarnya berarti Repost)
                unset($data['shares']);
                $data['repost'] = $this->parseIndonesianMetric($validated['share'] ?? 0);
                break;
        }

        // Update mode if metric_id is present (Fallback for frontend form action issues)
        $metricId = $request->input('metric_id');
        if (!empty($metricId)) {
            $metricId = preg_replace('/[^0-9]/', '', $metricId);
            $record = $modelClass::findOrFail($metricId);
            $record->update($data);
            return back()->with('success', 'Perubahan data berhasil disimpan!');
        }

        // Insert mode
        $modelClass::create($data);

        return back()->with('success', 'Data metrik ' . ucfirst($platformKey) . ' berhasil disimpan!');
    }

    public function updateMetric(Request $request, $platform, $id)
    {
        if (!isset(self::PLATFORM_MODELS[$platform])) {
            return back()->with('error', 'Platform tidak dikenali.');
        }

        $platformKey = $platform;
        $modelClass = self::PLATFORM_MODELS[$platformKey];
        // Pastikan $id hanya berisi angka, untuk jaga-jaga kalau frontend masih mengirim format seperti "IG-43"
        $id = preg_replace('/[^0-9]/', '', $id);
        $record = $modelClass::findOrFail($id);

        $validated = $request->validate([
            'category'     => 'nullable|string',
            'title'        => 'nullable|string',
            'publish_date' => 'nullable|date',
            'content_type' => 'nullable|string',
            'url'          => 'nullable|string',
            'reach'        => 'nullable|string',
            'views'        => 'nullable|string',
            'repost'       => 'nullable|string',
            'save'         => 'nullable|string',
            'interactions' => 'nullable|string',
            'like'         => 'nullable|string',
            'comment'      => 'nullable|string',
            'share'        => 'nullable|string',
            'subscribers'  => 'nullable|string',
            'peak_viewers' => 'nullable|string',
        ]);

        $kategoriId = $record->kategori_id;
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
            'likes'          => $this->parseIndonesianMetric($validated['like'] ?? 0),
            'comments'       => $this->parseIndonesianMetric($validated['comment'] ?? 0),
            'shares'         => $this->parseIndonesianMetric($validated['share'] ?? 0),
        ];

        switch ($platformKey) {
            case 'facebook':
                $data['jenis_konten'] = $validated['content_type'] ?? 'Image Post';
                $data['views']        = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['saves']        = $this->parseIndonesianMetric($validated['save'] ?? 0);
                break;

            case 'instagram':
                $data['jenis_konten'] = $validated['content_type'] ?? 'Feed Post';
                $data['reach']        = $this->parseIndonesianMetric($validated['reach'] ?? 0);
                $data['views']        = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['repost']       = $this->parseIndonesianMetric($validated['repost'] ?? 0);
                break;

            case 'tiktok':
                $data['jenis_konten']    = $validated['content_type'] ?? 'Short Video';
                $data['tayangan']        = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['total_interaksi'] = $this->parseIndonesianMetric($validated['interactions'] ?? 0);
                $data['saves']           = $this->parseIndonesianMetric($validated['save'] ?? 0);
                break;

            case 'yt-live':
                $data['jumlah_penayangan']     = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['penambahan_subscriber'] = $this->parseIndonesianMetric($validated['subscribers'] ?? 0);
                $data['penonton_puncak']       = $this->parseIndonesianMetric($validated['peak_viewers'] ?? 0);
                break;

            case 'yt-video':
                $data['jumlah_penayangan']     = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['penambahan_subscriber'] = $this->parseIndonesianMetric($validated['subscribers'] ?? 0);
                break;

            case 'yt-shorts':
                $data['jumlah_penayangan']     = $this->parseIndonesianMetric($validated['views'] ?? 0);
                $data['penambahan_subscriber'] = $this->parseIndonesianMetric($validated['subscribers'] ?? 0);
                unset($data['shares']);
                $data['repost'] = $this->parseIndonesianMetric($validated['share'] ?? 0);
                break;
        }

        $record->update($data);

        return back()->with('success', 'Perubahan data berhasil disimpan!');
    }

    public function destroyMetric(Request $request, $platform, $id)
    {
        if (!isset(self::PLATFORM_MODELS[$platform])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Platform tidak dikenali.'], 400);
            }
            return back()->with('error', 'Platform tidak dikenali.');
        }

        $platformKey = $platform;
        $modelClass = self::PLATFORM_MODELS[$platformKey];
        // Pastikan $id hanya berisi angka
        $id = preg_replace('/[^0-9]/', '', $id);
        
        try {
            $record = $modelClass::findOrFail($id);
            $record->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Data berhasil dihapus.'
                ]);
            }
            return redirect()->route('dashboard', ['tab' => 'input'])->with('success', 'Data riwayat berhasil dihapus!');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data gagal dihapus: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
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
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Jenis Konten', 'Link Konten', 'Views', 'Save', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->jenis_konten,
                        $row->tautan,
                        $row->views,
                        $row->saves,
                        $row->likes,
                        $row->comments,
                        $row->shares
                    ];
                };
                break;

            case 'instagram':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Jenis Konten', 'Link Konten', 'Reach', 'Views', 'Repost', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->jenis_konten,
                        $row->tautan,
                        $row->reach,
                        $row->views,
                        $row->repost,
                        $row->likes,
                        $row->comments,
                        $row->shares
                    ];
                };
                break;

            case 'tiktok':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Jenis Konten', 'Link Konten', 'Views', 'Interaksi (Engagement)', 'Save', 'Like', 'Comment', 'Share'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->jenis_konten,
                        $row->tautan,
                        $row->tayangan,
                        $row->total_interaksi,
                        $row->saves,
                        $row->likes,
                        $row->comments,
                        $row->shares
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
                        $row->likes,
                        $row->comments,
                        $row->shares
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
                        $row->likes,
                        $row->comments,
                        $row->shares
                    ];
                };
                break;

            case 'yt-shorts':
                $headers = ['Kategori', 'Tanggal Tayang', 'Judul Konten', 'Link Konten', 'Jumlah Penayangan (Views)', 'Penambahan Subscriber', 'Like', 'Comment', 'Repost'];
                $callback = function($row) {
                    return [
                        $row->kategori->nama ?? '',
                        $row->tanggal_tayang ? $row->tanggal_tayang->format('Y-m-d') : '',
                        $row->judul,
                        $row->tautan,
                        $row->jumlah_penayangan,
                        $row->penambahan_subscriber,
                        $row->likes,
                        $row->comments,
                        $row->repost
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
        if (auth()->check() && auth()->user()->role === 'user') {
            abort(403, 'Akses langsung tidak diizinkan. Gunakan fitur Permintaan Export.');
        }

        $user = auth()->user();
        if ($user && in_array($user->role, ['super-admin', 'admin'])) {
            $reason = $user->role === 'super-admin' ? 'Export langsung oleh Superadmin' : 'Export langsung oleh Admin';
            \App\Models\ExportRequest::create([
                'user_id' => $user->id,
                'admin_id' => null,
                'type' => 'excel',
                'reason' => $reason,
                'status' => 'approved',
                'filters' => $request->except(['_token', 'page']),
            ]);
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanExport($request), 'Laporan_Performa_Konten_' . date('Ymd_His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'user') {
            abort(403, 'Akses langsung tidak diizinkan. Gunakan fitur Permintaan Export.');
        }

        $user = auth()->user();
        if ($user && in_array($user->role, ['super-admin', 'admin'])) {
            $reason = $user->role === 'super-admin' ? 'Export langsung oleh Superadmin' : 'Export langsung oleh Admin';
            \App\Models\ExportRequest::create([
                'user_id'       => $user->id,
                'admin_id'      => null,
                'type'          => 'pdf',
                'export_source' => 'laporan',
                'reason'        => $reason,
                'status'        => 'approved',
                'filters'       => $request->except(['_token', 'page']),
            ]);
        }

        $export = new \App\Exports\LaporanExport($request);
        $data = $export->getViewData();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.Report.export_pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Performa_Konten_' . date('Ymd_His') . '.pdf');
    }

    public function exportRingkasanPdf(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'user') {
            abort(403, 'Akses langsung tidak diizinkan. Gunakan fitur Permintaan Export.');
        }

        $user = auth()->user();
        if ($user && in_array($user->role, ['super-admin', 'admin'])) {
            $reason = $user->role === 'super-admin' ? 'Export langsung oleh Superadmin' : 'Export langsung oleh Admin';
            
            $filters = $request->except(['_token', 'page']);
            $filters['export_source'] = 'ringkasan';
            
            \App\Models\ExportRequest::create([
                'user_id'       => $user->id,
                'admin_id'      => null,
                'type'          => 'pdf',
                'export_source' => 'ringkasan',
                'reason'        => $reason,
                'status'        => 'approved',
                'filters'       => $filters,
            ]);
        }

        $export = new \App\Exports\RingkasanExport($request);
        $data = $export->getViewData();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.Report.export_ringkasan_pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('Ringkasan_Performa_Konten_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Parses Indonesian metric formats into pure integers.
     * Examples:
     * - "50,2rb" -> 50200
     * - "1,5jt" -> 1500000
     * - "21.052" -> 21052
     * - "100" -> 100
     */
    private function parseIndonesianMetric($value): int
    {
        if (is_null($value) || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        $value = strtolower(trim($value));
        
        $multiplier = 1;
        if (str_ends_with($value, 'rb')) {
            $multiplier = 1000;
            $value = str_replace('rb', '', $value);
        } elseif (str_ends_with($value, 'jt')) {
            $multiplier = 1000000;
            $value = str_replace('jt', '', $value);
        }

        // Hapus titik ribuan (misal "21.052" -> "21052")
        $value = str_replace('.', '', $value);
        
        // Ubah koma desimal jadi titik ("50,2" -> "50.2")
        $value = str_replace(',', '.', $value);
        
        return (int)round((float)$value * $multiplier);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|in:' . implode(',', array_keys(self::PLATFORM_MODELS)),
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $platformKey = $request->input('platform');
        $modelClass = self::PLATFORM_MODELS[$platformKey];
        $platformSlug = self::PLATFORM_SLUG[$platformKey];
        $file = $request->file('excel_file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return back()->with('error', 'File Excel kosong atau tidak memiliki data.');
            }

            // Extract header and convert to lowercase for easier mapping
            $header = array_map(function($val) {
                return strtolower(trim((string)$val));
            }, $rows[0]);

            // Header Validation
            $hasKeyword = function($keywords) use ($header) {
                foreach ($keywords as $kw) {
                    foreach ($header as $col) {
                        if (str_contains($col, $kw)) return true;
                    }
                }
                return false;
            };

            $isValid = true;
            $platformName = ucfirst(str_replace('yt-', 'YouTube ', $platformKey));
            
            if ($platformKey === 'instagram') {
                if (!$hasKeyword(['reach', 'jangkauan', 'repost'])) $isValid = false;
            } elseif (str_starts_with($platformKey, 'yt-')) {
                if (!$hasKeyword(['sub', 'subscriber', 'puncak'])) $isValid = false;
            } elseif ($platformKey === 'facebook' || $platformKey === 'tiktok') {
                if ($hasKeyword(['reach', 'jangkauan', 'repost', 'sub', 'subscriber', 'puncak'])) $isValid = false;
            }

            if (!$isValid) {
                return redirect()->back()->with('error', "Format kolom tidak sesuai dengan platform {$platformName}");
            }

            $successCount = 0;
            $errorMessages = [];
            $minDate = null;
            $maxDate = null;

            $platformRow = Platform::where('slug', $platformSlug)->first();

            \Illuminate\Support\Facades\Log::info("Total baris terbaca dari file: " . count($rows));

            // Helper to get value by header name (contains)
            $getValue = function($searchArray, $currentRow) use ($header) {
                foreach ($searchArray as $search) {
                    foreach ($header as $colIndex => $colName) {
                        if (str_contains($colName, $search)) {
                            return $currentRow[$colIndex] ?? null;
                        }
                    }
                }
                return null;
            };

            foreach ($rows as $index => $row) {
                // Lewati baris pertama (Header)
                if ($index === 0) {
                    continue;
                }
                
                // Cek row kosong (abaikan jika semua cell null atau string kosong)
                $isEmpty = true;
                foreach ($row as $cell) {
                    if ($cell !== null && trim((string)$cell) !== '') {
                        $isEmpty = false;
                        break;
                    }
                }
                if ($isEmpty) {
                    continue;
                }

                try {

                // Parse Date - Strict
                $rawDate = $getValue(['tanggal', 'tgl', 'date'], $row);
                $parsedDate = null;
                if (!empty($rawDate)) {
                    if (is_numeric($rawDate)) {
                        try {
                            $parsedDate = Date::excelToDateTimeObject($rawDate)->format('Y-m-d');
                        } catch (\Exception $e) {
                            throw new \Exception("Format tanggal angka tidak valid ('{$rawDate}')");
                        }
                    } else {
                        try {
                            // Coba parse format teks DD/MM/YYYY atau YYYY-MM-DD
                            $rawDateStr = str_replace('/', '-', $rawDate);
                            $parsedDate = Carbon::parse($rawDateStr)->format('Y-m-d');
                        } catch (\Exception $e) {
                            throw new \Exception("Format tanggal teks tidak dapat dibaca ('{$rawDate}')");
                        }
                    }
                } else {
                    throw new \Exception("Kolom Tanggal tidak boleh kosong.");
                }

                // Update min/max date for flash message
                if (!$minDate || $parsedDate < $minDate) $minDate = $parsedDate;
                if (!$maxDate || $parsedDate > $maxDate) $maxDate = $parsedDate;

                // Cek / Buat Kategori
                $kategoriNama = $getValue(['kategori', 'category'], $row) ?? 'Umum';
                $kategoriId = null;
                if ($platformRow) {
                    $kategori = KategoriKonten::firstOrCreate([
                        'nama'        => $kategoriNama,
                        'platform_id' => $platformRow->id,
                    ]);
                    $kategoriId = $kategori->id;
                }

                // Data Umum
                $rawTautan = $getValue(['link', 'url', 'tautan'], $row);

                $data = [
                    'kategori_id'    => $kategoriId,
                    'judul'          => $getValue(['judul', 'title', 'topik'], $row) ?? '(Tanpa Judul)',
                    'tautan'         => !empty($rawTautan) ? $rawTautan : null,
                    'tanggal_tayang' => $parsedDate,
                    'likes'          => $this->parseIndonesianMetric($getValue(['like'], $row) ?? 0),
                    'comments'       => $this->parseIndonesianMetric($getValue(['comment', 'komen'], $row) ?? 0),
                    'shares'         => $this->parseIndonesianMetric($getValue(['share', 'bagikan'], $row) ?? 0),
                ];

                // Normalisasi dan Validasi Jenis Konten Khusus Platform
                $rawJenisKonten = $getValue(['jenis', 'type', 'tipe'], $row);
                
                $normalizeJenisKonten = function($input, $platform) {
                    $inputRaw = trim((string)$input);
                    $inputLower = strtolower($inputRaw);
                    
                    $defaults = [
                        'facebook'  => 'Image Post',
                        'instagram' => 'Feed Post',
                        'tiktok'    => 'Short Video',
                        'yt-video'  => 'Long Video',
                        'yt-shorts' => 'YouTube Shorts',
                        'yt-live'   => 'Live Stream'
                    ];
                    
                    if ($inputRaw === '') {
                        return $defaults[$platform] ?? 'Unknown';
                    }

                    $mappings = [
                        'facebook' => [
                            'image' => 'Image Post', 'post' => 'Image Post', 'gambar' => 'Image Post',
                            'video' => 'Video', 'vid' => 'Video',
                            'carousel' => 'Carousel', 'slide' => 'Carousel',
                            'link' => 'Link', 'tautan' => 'Link'
                        ],
                        'instagram' => [
                            'feed' => 'Feed Post', 'post' => 'Feed Post', 'image' => 'Feed Post',
                            'reel' => 'Reels', 'video' => 'Reels',
                            'story' => 'Story', 'sorotan' => 'Story',
                            'carousel' => 'Carousel', 'slide' => 'Carousel'
                        ],
                        'tiktok' => [
                            'short' => 'Short Video', 'video' => 'Short Video', 'vt' => 'Short Video',
                            'live' => 'Live Stream', 'stream' => 'Live Stream'
                        ],
                        'yt-video' => [
                            'long' => 'Long Video', 'video' => 'Long Video', 'yt' => 'Long Video'
                        ],
                        'yt-shorts' => [
                            'short' => 'YouTube Shorts', 'yt' => 'YouTube Shorts'
                        ],
                        'yt-live' => [
                            'live' => 'Live Stream', 'stream' => 'Live Stream'
                        ]
                    ];

                    $allowedMap = $mappings[$platform] ?? [];
                    foreach ($allowedMap as $keyword => $correctValue) {
                        if (str_contains($inputLower, $keyword)) {
                            return $correctValue;
                        }
                    }
                    
                    throw new \Exception("Jenis konten '{$inputRaw}' tidak dikenali/tidak diizinkan untuk platform ini.");
                };

                $jenisKontenValid = $normalizeJenisKonten($rawJenisKonten, $platformKey);

                switch ($platformKey) {
                    case 'facebook':
                        $data['jenis_konten'] = $jenisKontenValid;
                        $data['views']        = $this->parseIndonesianMetric($getValue(['view', 'tayangan'], $row) ?? 0);
                        $data['saves']        = $this->parseIndonesianMetric($getValue(['save', 'simpan'], $row) ?? 0);
                        break;
        
                    case 'instagram':
                        $data['jenis_konten'] = $jenisKontenValid;
                        $data['reach']        = $this->parseIndonesianMetric($getValue(['reach', 'jangkauan'], $row) ?? 0);
                        $data['views']        = $this->parseIndonesianMetric($getValue(['view', 'tayangan'], $row) ?? 0);
                        $data['repost']       = $this->parseIndonesianMetric($getValue(['repost'], $row) ?? 0);
                        break;
        
                    case 'tiktok':
                        $data['jenis_konten']    = $jenisKontenValid;
                        $data['tayangan']        = $this->parseIndonesianMetric($getValue(['view', 'tayangan'], $row) ?? 0);
                        $data['total_interaksi'] = $this->parseIndonesianMetric($getValue(['interaksi', 'interaction'], $row) ?? 0);
                        $data['saves']           = $this->parseIndonesianMetric($getValue(['save', 'simpan'], $row) ?? 0);
                        break;
        
                    case 'yt-live':
                        $data['jenis_konten']          = $jenisKontenValid;
                        $data['jumlah_penayangan']     = $this->parseIndonesianMetric($getValue(['view', 'penayangan', 'tayangan'], $row) ?? 0);
                        $data['penambahan_subscriber'] = $this->parseIndonesianMetric($getValue(['subscriber', 'sub'], $row) ?? 0);
                        $data['penonton_puncak']       = $this->parseIndonesianMetric($getValue(['peak', 'puncak'], $row) ?? 0);
                        break;
        
                    case 'yt-video':
                        $data['jenis_konten']          = $jenisKontenValid;
                        $data['jumlah_penayangan']     = $this->parseIndonesianMetric($getValue(['view', 'penayangan', 'tayangan'], $row) ?? 0);
                        $data['penambahan_subscriber'] = $this->parseIndonesianMetric($getValue(['subscriber', 'sub'], $row) ?? 0);
                        break;
        
                    case 'yt-shorts':
                        $data['jenis_konten']          = $jenisKontenValid;
                        $data['jumlah_penayangan']     = $this->parseIndonesianMetric($getValue(['view', 'penayangan', 'tayangan'], $row) ?? 0);
                        $data['penambahan_subscriber'] = $this->parseIndonesianMetric($getValue(['subscriber', 'sub'], $row) ?? 0);
                        unset($data['shares']);
                        $data['repost'] = $this->parseIndonesianMetric($getValue(['repost', 'share', 'bagikan'], $row) ?? 0);
                        break;
                }

                $modelClass::create($data);
                $successCount++;
                
                } catch (\Exception $e) {
                    $barisExcel = $index + 1;
                    $errMsg = "Baris {$barisExcel} gagal: " . $e->getMessage();
                    $errorMessages[] = $errMsg;
                    \Illuminate\Support\Facades\Log::error("Import Excel Error pada baris {$barisExcel}: " . $e->getMessage());
                }
            }

            $minDateStr = $minDate ? Carbon::parse($minDate)->format('d/m/Y') : '-';
            $maxDateStr = $maxDate ? Carbon::parse($maxDate)->format('d/m/Y') : '-';
            
            $platformName = ucfirst($platformSlug);
            if(str_starts_with($platformKey, 'yt-')) {
                $platformName = 'YouTube ' . ucfirst(explode('-', $platformKey)[1]);
            }

            $finalMsg = "Berhasil mengimpor {$successCount} data {$platformName} periode {$minDateStr} - {$maxDateStr}.";
            if (count($errorMessages) > 0) {
                // Tampilkan beberapa error pertama agar pesan tidak terlalu panjang
                $displayErrors = array_slice($errorMessages, 0, 3);
                $finalMsg .= " Namun ada " . count($errorMessages) . " baris yang gagal diimpor: " . implode(', ', $displayErrors) . (count($errorMessages) > 3 ? '...' : '');
                return back()->with('warning', $finalMsg);
            }

            return back()->with('success', $finalMsg);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Import Excel Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses file Excel: ' . $e->getMessage());
        }
    }
}